<?php

declare(strict_types=1);

namespace CakePhpAgent\Installer;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Editor\EditorAdapterInterface;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Extension\Extension;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Extension\ResolutionResult;
use CakePhpAgent\Filesystem\Filesystem;
use CakePhpAgent\PackagePaths;

/**
 * Copies package-managed rules/skills/agents into editor target directories.
 *
 * Never overwrites:
 * - .ai/** (project-owned)
 * - unmanaged files unless --force
 *
 * Prune only removes paths recorded in the installation lock file.
 */
final class KnowledgeInstaller
{
    /** @var list<string> */
    private const CORE_LAYERS = ['engineering', 'php', 'cakephp'];

    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly EditorRegistry $editors = new EditorRegistry(),
        private readonly InstallationStateStore $stateStore = new InstallationStateStore(),
        private readonly ExtensionResolver $extensionResolver = new ExtensionResolver(),
        private readonly string $packageRoot = '',
    ) {
    }

    /**
     * @return list<InstallAction>
     */
    public function plan(ProjectConfig $config, ?ResolutionResult $resolution = null): array
    {
        $packageRoot = $this->packageRoot !== '' ? $this->packageRoot : PackagePaths::root();
        $previous = $this->stateStore->load($config->projectRoot);
        $adapters = $this->editors->resolveMany($config->editors);
        $resolution ??= $this->extensionResolver->resolve($config);

        $planned = [];
        $managedRelatives = [];

        foreach ($adapters as $adapter) {
            foreach ($this->collectSources($packageRoot, $resolution->enabled) as $source) {
                $targetDir = $this->targetDirectory($adapter, $source['kind'], $config->projectRoot);
                if ($targetDir === null) {
                    continue;
                }

                $targetPath = $targetDir . '/' . $source['relative'];
                $relativeKey = $this->relativeToProject($config->projectRoot, $targetPath);
                $managedRelatives[$relativeKey] = true;

                $planned[] = $this->planFileAction(
                    config: $config,
                    previous: $previous,
                    sourcePath: $source['absolute'],
                    targetPath: $targetPath,
                    relativeKey: $relativeKey,
                    editor: $adapter->id(),
                    kind: $source['kind'],
                );
            }
        }

        if ($config->prune) {
            foreach ($previous->files as $relative => $meta) {
                if (isset($managedRelatives[$relative])) {
                    continue;
                }
                $targetPath = $config->projectRoot . '/' . $relative;
                $planned[] = new InstallAction(
                    action: InstallAction::PRUNE,
                    relativePath: $relative,
                    sourcePath: '',
                    targetPath: $targetPath,
                    editor: $meta['editor'],
                    kind: $meta['kind'],
                    reason: 'No longer present in package-managed set',
                );
            }
        }

        return $planned;
    }

    /**
     * @return array{actions: list<InstallAction>, state: InstallationState, resolution: ResolutionResult}
     */
    public function install(ProjectConfig $config): array
    {
        $resolution = $this->extensionResolver->resolve($config);
        $actions = $this->plan($config, $resolution);
        $packageVersion = $this->detectPackageVersion();
        $newFiles = [];

        foreach ($actions as $action) {
            if ($config->dryRun) {
                if (in_array($action->action, [InstallAction::CREATE, InstallAction::UPDATE], true)) {
                    $hash = $action->sourcePath !== '' && $this->filesystem->isFile($action->sourcePath)
                        ? $this->filesystem->hashFile($action->sourcePath)
                        : '';
                    $newFiles[$action->relativePath] = [
                        'hash' => $hash,
                        'editor' => $action->editor,
                        'kind' => $action->kind,
                    ];
                } elseif ($action->action === InstallAction::SKIP || $action->action === InstallAction::PRESERVE) {
                    $previous = $this->stateStore->load($config->projectRoot);
                    if (isset($previous->files[$action->relativePath])) {
                        $newFiles[$action->relativePath] = $previous->files[$action->relativePath];
                    } elseif ($this->filesystem->isFile($action->targetPath)) {
                        $newFiles[$action->relativePath] = [
                            'hash' => $this->filesystem->hashFile($action->targetPath),
                            'editor' => $action->editor,
                            'kind' => $action->kind,
                        ];
                    }
                }
                continue;
            }

            match ($action->action) {
                InstallAction::CREATE, InstallAction::UPDATE => $this->writeManagedFile($config, $action),
                InstallAction::PRUNE => $this->filesystem->remove($action->targetPath),
                default => null,
            };

            if (in_array($action->action, [InstallAction::CREATE, InstallAction::UPDATE, InstallAction::SKIP], true)) {
                $hash = $this->filesystem->isFile($action->targetPath)
                    ? $this->filesystem->hashFile($action->targetPath)
                    : ($action->sourcePath !== '' ? $this->filesystem->hashFile($action->sourcePath) : '');
                $newFiles[$action->relativePath] = [
                    'hash' => $hash,
                    'editor' => $action->editor,
                    'kind' => $action->kind,
                ];
            } elseif ($action->action === InstallAction::PRESERVE) {
                $previous = $this->stateStore->load($config->projectRoot);
                if (isset($previous->files[$action->relativePath])) {
                    $newFiles[$action->relativePath] = $previous->files[$action->relativePath];
                }
            }
        }

        if (!$config->prune) {
            $previous = $this->stateStore->load($config->projectRoot);
            foreach ($previous->files as $relative => $meta) {
                if (!isset($newFiles[$relative]) && $this->filesystem->exists($config->projectRoot . '/' . $relative)) {
                    $newFiles[$relative] = $meta;
                }
            }
        }

        $state = InstallationState::empty($packageVersion)->withInstallResult(
            packageVersion: $packageVersion,
            editors: $config->editors,
            extensions: $resolution->enabledIds(),
            files: $newFiles,
        );

        if (!$config->dryRun) {
            $this->stateStore->save($config->projectRoot, $state);
        }

        return ['actions' => $actions, 'state' => $state, 'resolution' => $resolution];
    }

    private function planFileAction(
        ProjectConfig $config,
        InstallationState $previous,
        string $sourcePath,
        string $targetPath,
        string $relativeKey,
        string $editor,
        string $kind,
    ): InstallAction {
        if ($this->isProjectOwnedPath($relativeKey)) {
            return new InstallAction(
                action: InstallAction::PRESERVE,
                relativePath: $relativeKey,
                sourcePath: $sourcePath,
                targetPath: $targetPath,
                editor: $editor,
                kind: $kind,
                reason: 'Project-owned path under .ai/',
            );
        }

        $exists = $this->filesystem->exists($targetPath);
        $sourceHash = $this->filesystem->hashFile($sourcePath);

        if (!$exists) {
            return new InstallAction(
                action: InstallAction::CREATE,
                relativePath: $relativeKey,
                sourcePath: $sourcePath,
                targetPath: $targetPath,
                editor: $editor,
                kind: $kind,
            );
        }

        $wasManaged = isset($previous->files[$relativeKey]);
        $targetHash = $this->filesystem->isFile($targetPath)
            ? $this->filesystem->hashFile($targetPath)
            : '';

        if ($wasManaged) {
            if ($targetHash === $sourceHash) {
                return new InstallAction(
                    action: InstallAction::SKIP,
                    relativePath: $relativeKey,
                    sourcePath: $sourcePath,
                    targetPath: $targetPath,
                    editor: $editor,
                    kind: $kind,
                    reason: 'Already up to date',
                );
            }

            if ($config->force || $targetHash === $previous->files[$relativeKey]['hash']) {
                return new InstallAction(
                    action: InstallAction::UPDATE,
                    relativePath: $relativeKey,
                    sourcePath: $sourcePath,
                    targetPath: $targetPath,
                    editor: $editor,
                    kind: $kind,
                    reason: $config->force ? 'Forced update' : 'Managed file changed upstream',
                );
            }

            return new InstallAction(
                action: InstallAction::PRESERVE,
                relativePath: $relativeKey,
                sourcePath: $sourcePath,
                targetPath: $targetPath,
                editor: $editor,
                kind: $kind,
                reason: 'Local modifications to managed file; use --force to overwrite',
            );
        }

        if ($config->force) {
            return new InstallAction(
                action: InstallAction::UPDATE,
                relativePath: $relativeKey,
                sourcePath: $sourcePath,
                targetPath: $targetPath,
                editor: $editor,
                kind: $kind,
                reason: 'Forced overwrite of unmanaged file',
            );
        }

        return new InstallAction(
            action: InstallAction::PRESERVE,
            relativePath: $relativeKey,
            sourcePath: $sourcePath,
            targetPath: $targetPath,
            editor: $editor,
            kind: $kind,
            reason: 'Existing unmanaged file preserved',
        );
    }

    private function writeManagedFile(ProjectConfig $config, InstallAction $action): void
    {
        if ($config->symlink) {
            $this->filesystem->symlink($action->sourcePath, $action->targetPath);

            return;
        }

        if ($this->filesystem->exists($action->targetPath)) {
            $this->filesystem->remove($action->targetPath);
        }
        $this->filesystem->copy($action->sourcePath, $action->targetPath);
    }

    /**
     * @param list<Extension> $enabledExtensions
     * @return list<array{kind: string, relative: string, absolute: string}>
     */
    private function collectSources(string $packageRoot, array $enabledExtensions): array
    {
        $sources = [];

        foreach (['rule' => 'rules', 'skill' => 'skills'] as $kind => $dirname) {
            $base = $packageRoot . '/' . $dirname;
            if (!$this->filesystem->isDir($base)) {
                continue;
            }

            foreach (self::CORE_LAYERS as $layer) {
                $layerDir = $base . '/' . $layer;
                if (!$this->filesystem->isDir($layerDir)) {
                    continue;
                }

                foreach ($this->filesystem->listFilesRelative($layerDir) as $relative) {
                    if (str_ends_with($relative, '.gitkeep')) {
                        continue;
                    }
                    $sources[] = [
                        'kind' => $kind,
                        'relative' => $layer . '/' . $relative,
                        'absolute' => $layerDir . '/' . $relative,
                    ];
                }
            }
        }

        $agentsDir = $packageRoot . '/agents';
        if ($this->filesystem->isDir($agentsDir)) {
            foreach ($this->filesystem->listFilesRelative($agentsDir) as $relative) {
                if (str_ends_with($relative, '.gitkeep')) {
                    continue;
                }
                $sources[] = [
                    'kind' => 'agent',
                    'relative' => $relative,
                    'absolute' => $agentsDir . '/' . $relative,
                ];
            }
        }

        foreach ($enabledExtensions as $extension) {
            foreach ($this->collectExtensionSources($extension) as $source) {
                $sources[] = $source;
            }
        }

        return $sources;
    }

    /**
     * @return list<array{kind: string, relative: string, absolute: string}>
     */
    private function collectExtensionSources(Extension $extension): array
    {
        $sources = [];
        $id = $extension->id();

        $rulesDir = $extension->rulesDirectory();
        if ($this->filesystem->isDir($rulesDir)) {
            foreach ($this->filesystem->listFilesRelative($rulesDir) as $relative) {
                if (str_ends_with($relative, '.gitkeep')) {
                    continue;
                }
                $sources[] = [
                    'kind' => 'rule',
                    'relative' => 'extensions/' . $id . '/' . $relative,
                    'absolute' => $rulesDir . '/' . $relative,
                ];
            }
        }

        $skillsDir = $extension->skillsDirectory();
        if ($this->filesystem->isDir($skillsDir)) {
            foreach ($this->filesystem->listFilesRelative($skillsDir) as $relative) {
                if (str_ends_with($relative, '.gitkeep')) {
                    continue;
                }
                $sources[] = [
                    'kind' => 'skill',
                    'relative' => 'extensions/' . $id . '/' . $relative,
                    'absolute' => $skillsDir . '/' . $relative,
                ];
            }
        }

        $agentsDir = $extension->agentsDirectory();
        if ($this->filesystem->isDir($agentsDir)) {
            foreach ($this->filesystem->listFilesRelative($agentsDir) as $relative) {
                if (str_ends_with($relative, '.gitkeep')) {
                    continue;
                }
                $sources[] = [
                    'kind' => 'agent',
                    'relative' => 'extensions/' . $id . '/' . $relative,
                    'absolute' => $agentsDir . '/' . $relative,
                ];
            }
        }

        return $sources;
    }

    private function targetDirectory(EditorAdapterInterface $adapter, string $kind, string $projectRoot): ?string
    {
        return match ($kind) {
            'rule' => $adapter->supportsRules() ? $adapter->rulesDirectory($projectRoot) : null,
            'skill' => $adapter->supportsSkills() ? $adapter->skillsDirectory($projectRoot) : null,
            'agent' => $adapter->supportsAgents() ? $adapter->agentsDirectory($projectRoot) : null,
            default => null,
        };
    }

    private function relativeToProject(string $projectRoot, string $absolutePath): string
    {
        $root = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $path = str_replace('\\', '/', $absolutePath);
        if (str_starts_with($path, $root . '/')) {
            return substr($path, strlen($root) + 1);
        }

        return $path;
    }

    private function isProjectOwnedPath(string $relativeKey): bool
    {
        return str_starts_with($relativeKey, ProjectConfig::PROJECT_AI_DIR . '/');
    }

    private function detectPackageVersion(): string
    {
        $composer = ($this->packageRoot !== '' ? $this->packageRoot : PackagePaths::root()) . '/composer.json';
        if (!$this->filesystem->isFile($composer)) {
            return '0.1.0';
        }

        try {
            $data = json_decode($this->filesystem->read($composer), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return '0.1.0';
        }

        return is_array($data) && isset($data['version']) && is_string($data['version'])
            ? $data['version']
            : '0.1.0';
    }
}
