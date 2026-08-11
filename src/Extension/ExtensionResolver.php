<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Discovery\ComposerMetadataReader;
use CakePhpAgent\Discovery\VersionMatcher;
use RuntimeException;

/**
 * Resolve which extensions should be active for a project.
 *
 * Order of operations:
 * 1. Load all manifests
 * 2. Detect Composer-package matches (incl. version constraints)
 * 3. Apply explicit enable / disable
 * 4. Activate integrations when all required extensions are present
 * 5. Resolve dependsOn transitively
 * 6. Enforce conflicts and cycles
 * 7. Produce deterministic ordered result
 */
final class ExtensionResolver
{
    public function __construct(
        private readonly ExtensionRegistry $registry = new ExtensionRegistry(),
        private readonly ComposerMetadataReader $composerReader = new ComposerMetadataReader(),
        private readonly VersionMatcher $versions = new VersionMatcher(),
    ) {
    }

    public function resolve(ProjectConfig $config): ResolutionResult
    {
        $packages = $this->composerReader->installedPackages($config->projectRoot);
        $cakephpVersion = $packages['cakephp/cakephp'] ?? null;

        /** @var array<string, ExtensionDecision> $decisions */
        $decisions = [];
        /** @var array<string, Extension> $candidates */
        $candidates = [];

        foreach ($this->registry->all() as $extension) {
            $manifest = $extension->manifest;
            $id = $manifest->id;

            // Explicit disable always wins.
            if (in_array($id, $config->disableExtensions, true)) {
                $decisions[$id] = new ExtensionDecision(
                    $id,
                    ExtensionDecision::DISABLED,
                    'Explicitly disabled in project configuration',
                    $extension,
                );
                continue;
            }

            // Explicit enable.
            if (in_array($id, $config->enableExtensions, true)) {
                $compat = $this->checkRequires($manifest, $packages, $cakephpVersion);
                if ($compat !== null) {
                    $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::INCOMPATIBLE, $compat, $extension);
                    continue;
                }
                $candidates[$id] = $extension;
                $decisions[$id] = new ExtensionDecision(
                    $id,
                    ExtensionDecision::ENABLED,
                    'Explicitly enabled in project configuration',
                    $extension,
                );
                continue;
            }

            // Integration packs are handled after base candidates are known.
            if ($manifest->type === ExtensionManifest::TYPE_INTEGRATION
                || $manifest->activateWhenAllExtensionsPresent !== []
            ) {
                continue;
            }

            // Architecture / infrastructure without detect require explicit enable.
            if ($manifest->type !== ExtensionManifest::TYPE_COMPOSER && $manifest->detectComposer === []) {
                $decisions[$id] = new ExtensionDecision(
                    $id,
                    ExtensionDecision::UNDETECTED,
                    'Requires explicit enable (non-auto-detect type)',
                    $extension,
                );
                continue;
            }

            $detection = $this->detectComposerPackage($manifest, $packages);
            if ($detection['status'] === ExtensionDecision::INCOMPATIBLE) {
                $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::INCOMPATIBLE, $detection['reason'], $extension);
                continue;
            }

            if ($detection['status'] === ExtensionDecision::UNDETECTED) {
                $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::UNDETECTED, $detection['reason'], $extension);
                continue;
            }

            if (!$manifest->defaultEnabledWhenDetected) {
                $decisions[$id] = new ExtensionDecision(
                    $id,
                    ExtensionDecision::DISABLED,
                    'Detected but defaultEnabledWhenDetected=false',
                    $extension,
                );
                continue;
            }

            $compat = $this->checkRequires($manifest, $packages, $cakephpVersion);
            if ($compat !== null) {
                $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::INCOMPATIBLE, $compat, $extension);
                continue;
            }

            $candidates[$id] = $extension;
            $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::ENABLED, $detection['reason'], $extension);
        }

        // Activate integration packs when all required extensions are among candidates.
        foreach ($this->registry->all() as $extension) {
            $manifest = $extension->manifest;
            $id = $manifest->id;
            if (isset($decisions[$id]) && $decisions[$id]->status === ExtensionDecision::DISABLED) {
                continue;
            }

            $required = $manifest->activateWhenAllExtensionsPresent;
            if ($required === [] && $manifest->type !== ExtensionManifest::TYPE_INTEGRATION) {
                continue;
            }

            if ($required === []) {
                continue;
            }

            if (isset($candidates[$id])) {
                continue;
            }

            $missing = [];
            foreach ($required as $depId) {
                if (!isset($candidates[$depId])) {
                    $missing[] = $depId;
                }
            }

            if ($missing !== []) {
                if (!isset($decisions[$id])) {
                    $decisions[$id] = new ExtensionDecision(
                        $id,
                        ExtensionDecision::UNDETECTED,
                        'Integration waiting on: ' . implode(', ', $missing),
                        $extension,
                    );
                }
                continue;
            }

            if (in_array($id, $config->disableExtensions, true)) {
                continue;
            }

            $compat = $this->checkRequires($manifest, $packages, $cakephpVersion);
            if ($compat !== null) {
                $decisions[$id] = new ExtensionDecision($id, ExtensionDecision::INCOMPATIBLE, $compat, $extension);
                continue;
            }

            $candidates[$id] = $extension;
            $decisions[$id] = new ExtensionDecision(
                $id,
                ExtensionDecision::ENABLED,
                'Integration activated because all required extensions are present: ' . implode(', ', $required),
                $extension,
            );
        }

        // Resolve dependsOn transitively for candidates.
        $resolved = $this->resolveDependencies($candidates);

        // Conflicts
        $this->assertNoConflicts($resolved);

        // Deterministic order: dependency-first topological, then id.
        $ordered = $this->topoSort($resolved);

        // Refresh decisions for anything that became enabled via dependsOn.
        foreach ($ordered as $extension) {
            $id = $extension->id();
            if (!isset($decisions[$id]) || $decisions[$id]->status !== ExtensionDecision::ENABLED) {
                $decisions[$id] = new ExtensionDecision(
                    $id,
                    ExtensionDecision::ENABLED,
                    'Enabled as a dependency of another extension',
                    $extension,
                );
            }
        }

        // Stable decision list sorted by id.
        ksort($decisions);

        return new ResolutionResult($ordered, array_values($decisions));
    }

    /**
     * @param array<string, string> $packages
     * @return array{status: string, reason: string}
     */
    private function detectComposerPackage(ExtensionManifest $manifest, array $packages): array
    {
        if ($manifest->detectComposer === []) {
            return [
                'status' => ExtensionDecision::UNDETECTED,
                'reason' => 'No Composer detection rules',
            ];
        }

        $matched = [];
        $incompatible = [];

        foreach ($manifest->detectComposer as $rule) {
            $package = $rule['package'];
            $constraint = $rule['constraint'];

            if (!isset($packages[$package])) {
                continue;
            }

            $installed = $packages[$package];
            if ($constraint === null || $constraint === '' || $constraint === '*') {
                $matched[] = sprintf('%s (%s)', $package, $installed);
                continue;
            }

            if ($this->versions->satisfies($installed, $constraint)) {
                $matched[] = sprintf('%s (%s satisfies %s)', $package, $installed, $constraint);
            } else {
                $incompatible[] = sprintf(
                    '%s present as %s but does not satisfy %s',
                    $package,
                    $installed,
                    $constraint
                );
            }
        }

        if ($matched !== []) {
            return [
                'status' => ExtensionDecision::ENABLED,
                'reason' => 'Detected via Composer: ' . implode('; ', $matched),
            ];
        }

        if ($incompatible !== []) {
            return [
                'status' => ExtensionDecision::INCOMPATIBLE,
                'reason' => implode('; ', $incompatible),
            ];
        }

        $wanted = array_map(
            static fn (array $r): string => $r['package'],
            $manifest->detectComposer
        );

        return [
            'status' => ExtensionDecision::UNDETECTED,
            'reason' => 'Composer package not present: ' . implode(', ', $wanted),
        ];
    }

    /**
     * @param array<string, string> $packages
     */
    private function checkRequires(ExtensionManifest $manifest, array $packages, ?string $cakephpVersion): ?string
    {
        foreach ($manifest->requires as $key => $constraint) {
            if ($key === 'cakephp' || $key === 'cakephp/cakephp') {
                if ($cakephpVersion === null) {
                    return 'Requires cakephp but cakephp/cakephp was not found in Composer metadata';
                }
                if (!$this->versions->satisfies($cakephpVersion, $constraint)) {
                    return sprintf(
                        'Requires cakephp %s but project has %s',
                        $constraint,
                        $cakephpVersion
                    );
                }
                continue;
            }

            if ($key === 'php') {
                if (!$this->versions->satisfies(PHP_VERSION, $constraint)) {
                    return sprintf('Requires php %s but runtime is %s', $constraint, PHP_VERSION);
                }
                continue;
            }

            if (!isset($packages[$key])) {
                return sprintf('Requires Composer package %s (%s) which is not installed', $key, $constraint);
            }
            if (!$this->versions->satisfies($packages[$key], $constraint)) {
                return sprintf(
                    'Requires %s %s but project has %s',
                    $key,
                    $constraint,
                    $packages[$key]
                );
            }
        }

        return null;
    }

    /**
     * @param array<string, Extension> $candidates
     * @return array<string, Extension>
     */
    private function resolveDependencies(array $candidates): array
    {
        $resolved = $candidates;
        $queue = array_keys($candidates);

        while ($queue !== []) {
            $id = array_shift($queue);
            $extension = $resolved[$id] ?? $this->registry->get($id);
            foreach ($extension->manifest->dependsOn as $depId) {
                if (isset($resolved[$depId])) {
                    continue;
                }
                if (!$this->registry->has($depId)) {
                    throw new RuntimeException(sprintf(
                        'Extension "%s" depends on unknown extension "%s".',
                        $id,
                        $depId
                    ));
                }
                $resolved[$depId] = $this->registry->get($depId);
                $queue[] = $depId;
            }
        }

        return $resolved;
    }

    /**
     * @param array<string, Extension> $resolved
     */
    private function assertNoConflicts(array $resolved): void
    {
        foreach ($resolved as $id => $extension) {
            foreach ($extension->manifest->conflictsWith as $conflictId) {
                if (isset($resolved[$conflictId])) {
                    throw new RuntimeException(sprintf(
                        'Extension conflict: "%s" conflicts with "%s".',
                        $id,
                        $conflictId
                    ));
                }
            }
        }
    }

    /**
     * @param array<string, Extension> $resolved
     * @return list<Extension>
     */
    private function topoSort(array $resolved): array
    {
        /** @var array<string, list<string>> $edges */
        $edges = [];
        /** @var array<string, int> $indegree */
        $indegree = [];

        foreach ($resolved as $id => $extension) {
            $indegree[$id] = $indegree[$id] ?? 0;
            $edges[$id] = $edges[$id] ?? [];
            foreach ($extension->manifest->dependsOn as $depId) {
                if (!isset($resolved[$depId])) {
                    continue;
                }
                $edges[$depId][] = $id;
                $indegree[$id] = ($indegree[$id] ?? 0) + 1;
                $indegree[$depId] = $indegree[$depId] ?? 0;
            }
        }

        $ready = [];
        foreach ($indegree as $id => $degree) {
            if ($degree === 0) {
                $ready[] = $id;
            }
        }
        sort($ready);

        $ordered = [];
        while ($ready !== []) {
            $id = array_shift($ready);
            $ordered[] = $resolved[$id];
            foreach ($edges[$id] ?? [] as $child) {
                $indegree[$child]--;
                if ($indegree[$child] === 0) {
                    $ready[] = $child;
                    sort($ready);
                }
            }
        }

        if (count($ordered) !== count($resolved)) {
            throw new RuntimeException('Extension dependency cycle detected.');
        }

        return $ordered;
    }
}
