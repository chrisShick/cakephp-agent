<?php

declare(strict_types=1);

namespace CakePhpAgent\Command;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Configuration\ProjectConfigLoader;
use CakePhpAgent\Discovery\ComposerMetadataReader;
use CakePhpAgent\Discovery\ProjectRootLocator;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Extension\ExtensionDecision;
use CakePhpAgent\Extension\ExtensionRegistry;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Installer\InstallAction;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use CakePhpAgent\Validation\ContentValidator;
use Throwable;

final class Application
{
    public function __construct(
        private readonly ProjectRootLocator $rootLocator = new ProjectRootLocator(),
        private readonly ProjectConfigLoader $configLoader = new ProjectConfigLoader(),
        private readonly ComposerMetadataReader $composerReader = new ComposerMetadataReader(),
        private readonly EditorRegistry $editorRegistry = new EditorRegistry(),
        private readonly KnowledgeInstaller $installer = new KnowledgeInstaller(),
        private readonly ExtensionResolver $extensionResolver = new ExtensionResolver(),
        private readonly ExtensionRegistry $extensionRegistry = new ExtensionRegistry(),
        private readonly ManifestLoader $manifestLoader = new ManifestLoader(),
    ) {
    }

    /**
     * @param list<string> $argv
     */
    public function run(array $argv): int
    {
        $command = $argv[1] ?? 'help';
        $options = $this->parseOptions(array_slice($argv, 2));

        try {
            return match ($command) {
                'help', '--help', '-h' => $this->help(),
                'install' => $this->install($options),
                'detect' => $this->detect($options),
                'extensions' => $this->extensions($options),
                'explain' => $this->explain($options),
                'validate' => $this->validate(),
                'doctor' => $this->doctor($options),
                'version', '--version', '-V' => $this->version(),
                default => $this->unknown($command),
            };
        } catch (Throwable $e) {
            fwrite(STDERR, 'Error: ' . $e->getMessage() . PHP_EOL);

            return 1;
        }
    }

    private function help(): int
    {
        echo <<<'HELP'
CakePHP Agent — AI engineering knowledge for CakePHP 5

Usage:
  cakephp-agent <command> [options]

Commands:
  install      Install rules/skills for core + enabled extensions
  detect       Show Composer capabilities and enabled extensions
  extensions   List known extension packs and status for a project
  explain      Explain why each extension is enabled or not
  validate     Validate package content, manifests, knowledge, evaluations
  doctor       Sanity-check project + package
  version      Show package version
  help         Show this help

Install options:
  --editor=cursor|claude|codex|all   Target editor(s) (default: cursor)
  --extension=ID                     Force-enable extension (repeatable / comma-separated)
  --without=ID                       Force-disable extension (repeatable / comma-separated)
  --force                            Overwrite managed / conflicting files
  --symlink                          Symlink instead of copy
  --prune                            Remove previously managed files no longer present
  --dry-run                          Show actions without writing
  --verbose                          Verbose output
  --project=PATH                     Project root (default: discover from cwd)

Project-owned overlays live under .ai/ and are never overwritten.

HELP;

        return 0;
    }

    private function version(): int
    {
        echo 'cakephp-agent 0.1.0' . PHP_EOL;

        return 0;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function install(array $options): int
    {
        $config = $this->resolveConfig($options);
        $result = $this->installer->install($config);
        $actions = $result['actions'];
        $resolution = $result['resolution'];

        echo 'CakePHP Agent' . PHP_EOL . PHP_EOL;
        echo 'Project:' . PHP_EOL;
        echo '  ' . $config->projectRoot . PHP_EOL . PHP_EOL;
        echo 'Editors: ' . implode(', ', $config->editors) . PHP_EOL;
        echo 'Enabled extensions: ' . (
            $resolution->enabledIds() === []
                ? '(none)'
                : implode(', ', $resolution->enabledIds())
        ) . PHP_EOL;
        if ($config->dryRun) {
            echo 'Mode: dry-run' . PHP_EOL;
        }
        echo PHP_EOL . 'Actions:' . PHP_EOL;

        $counts = [
            InstallAction::CREATE => 0,
            InstallAction::UPDATE => 0,
            InstallAction::SKIP => 0,
            InstallAction::PRESERVE => 0,
            InstallAction::PRUNE => 0,
        ];

        foreach ($actions as $action) {
            $counts[$action->action] = ($counts[$action->action] ?? 0) + 1;
            if ($config->verbose || !in_array($action->action, [InstallAction::SKIP], true)) {
                $suffix = $action->reason !== '' ? ' (' . $action->reason . ')' : '';
                echo sprintf("  [%s] %s%s\n", strtoupper($action->action), $action->relativePath, $suffix);
            }
        }

        echo PHP_EOL;
        echo sprintf(
            "Summary: %d create, %d update, %d skip, %d preserve, %d prune\n",
            $counts[InstallAction::CREATE],
            $counts[InstallAction::UPDATE],
            $counts[InstallAction::SKIP],
            $counts[InstallAction::PRESERVE],
            $counts[InstallAction::PRUNE],
        );

        if ($config->dryRun) {
            echo 'Dry-run complete. No files written.' . PHP_EOL;
        } else {
            echo 'Project-owned files under .ai/ preserved.' . PHP_EOL;
            echo 'Installation complete.' . PHP_EOL;
        }

        return 0;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function detect(array $options): int
    {
        $config = $this->resolveConfig($options);
        $packages = $this->composerReader->installedPackages($config->projectRoot);
        $resolution = $this->extensionResolver->resolve($config);

        echo 'CakePHP Agent' . PHP_EOL . PHP_EOL;
        echo 'Project:' . PHP_EOL;
        echo '  ' . $config->projectRoot . PHP_EOL . PHP_EOL;

        $php = $packages['php'] ?? PHP_VERSION;
        $cake = $packages['cakephp/cakephp'] ?? '(not detected)';
        echo 'Detected:' . PHP_EOL;
        echo '  PHP ' . $php . PHP_EOL;
        echo '  CakePHP ' . $cake . PHP_EOL . PHP_EOL;

        echo 'Composer capabilities:' . PHP_EOL;
        $interesting = [
            'cakephp/cakephp',
            'cakephp/authentication',
            'cakephp/authorization',
            'friendsofcake/crud',
            'friendsofcake/search',
            'cakephp-agent/fake-plugin',
            'cakephp-agent/fake-addon',
        ];
        foreach ($interesting as $name) {
            if (isset($packages[$name])) {
                echo sprintf("  ✓ %s (%s)\n", $name, $packages[$name]);
            } else {
                echo sprintf("  · %s (not present)\n", $name);
            }
        }

        echo PHP_EOL . 'Enabled knowledge packs:' . PHP_EOL;
        echo "  ✓ engineering\n  ✓ php\n  ✓ cakephp\n";
        foreach ($resolution->enabled as $extension) {
            echo sprintf("  ✓ %s\n", $extension->id());
        }

        $incompatible = array_filter(
            $resolution->decisions,
            static fn (ExtensionDecision $d): bool => $d->status === ExtensionDecision::INCOMPATIBLE
        );
        if ($incompatible !== []) {
            echo PHP_EOL . 'Incompatible:' . PHP_EOL;
            foreach ($incompatible as $decision) {
                echo sprintf("  ✗ %s — %s\n", $decision->extensionId, $decision->reason);
            }
        }

        return 0;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function extensions(array $options): int
    {
        $config = $this->resolveConfig($options);
        $resolution = $this->extensionResolver->resolve($config);

        echo 'Known extensions:' . PHP_EOL;
        foreach ($resolution->decisions as $decision) {
            $mark = match ($decision->status) {
                ExtensionDecision::ENABLED => '✓',
                ExtensionDecision::INCOMPATIBLE => '✗',
                ExtensionDecision::DISABLED => '–',
                default => '·',
            };
            echo sprintf("  %s %-28s [%s]\n", $mark, $decision->extensionId, $decision->status);
        }

        return 0;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function explain(array $options): int
    {
        $config = $this->resolveConfig($options);
        $resolution = $this->extensionResolver->resolve($config);

        echo 'Extension decisions for ' . $config->projectRoot . PHP_EOL . PHP_EOL;
        foreach ($resolution->decisions as $decision) {
            echo sprintf("[%s] %s\n", strtoupper($decision->status), $decision->extensionId);
            echo '  ' . $decision->reason . PHP_EOL . PHP_EOL;
        }

        return 0;
    }

    private function validate(): int
    {
        $root = PackagePaths::root();
        $errors = [];

        foreach (['rules/engineering', 'rules/php', 'rules/cakephp', 'skills', 'schemas', 'src', 'extensions', 'knowledge/decisions', 'evaluations'] as $rel) {
            if (!is_dir($root . '/' . $rel)) {
                $errors[] = "Missing directory: {$rel}";
            }
        }

        try {
            $loaded = $this->manifestLoader->loadAll($root);
            $ids = [];
            foreach ($loaded as $extension) {
                $id = $extension->id();
                if (isset($ids[$id])) {
                    $errors[] = sprintf('Duplicate extension id "%s".', $id);
                }
                $ids[$id] = true;
            }
            echo sprintf("Loaded %d extension manifest(s).\n", count($loaded));
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }

        $contentErrors = (new ContentValidator())->validate();
        if ($contentErrors === []) {
            echo 'Knowledge, evaluations, skills, and CakePHP rules OK.' . PHP_EOL;
        } else {
            $errors = array_merge($errors, $contentErrors);
        }

        if ($errors === []) {
            echo 'Content layout OK.' . PHP_EOL;

            return 0;
        }

        foreach ($errors as $error) {
            fwrite(STDERR, $error . PHP_EOL);
        }

        return 1;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function doctor(array $options): int
    {
        $projectRoot = $this->projectRoot($options);
        echo 'Doctor' . PHP_EOL;
        echo '  Project root: ' . $projectRoot . PHP_EOL;
        echo '  Package root: ' . PackagePaths::root() . PHP_EOL;
        echo '  PHP: ' . PHP_VERSION . PHP_EOL;
        echo '  Editors supported: ' . implode(', ', $this->editorRegistry->ids()) . PHP_EOL;
        echo '  Extensions registered: ' . implode(', ', $this->extensionRegistry->ids()) . PHP_EOL;
        echo '  .ai overlay: ' . (is_dir($projectRoot . '/.ai') ? 'present' : 'not present') . PHP_EOL;
        echo '  Lock file: ' . (is_file($projectRoot . '/' . ProjectConfig::LOCK_FILENAME) ? 'present' : 'not present') . PHP_EOL;

        return 0;
    }

    private function unknown(string $command): int
    {
        fwrite(STDERR, sprintf('Unknown command "%s". Run cakephp-agent help.' . PHP_EOL, $command));

        return 1;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function resolveConfig(array $options): ProjectConfig
    {
        $projectRoot = $this->projectRoot($options);
        $base = $this->configLoader->load($projectRoot);

        $editors = null;
        if (isset($options['editor'])) {
            $editors = $options['editor'] === 'all'
                ? ['cursor', 'claude', 'codex']
                : [(string) $options['editor']];
        }

        $enable = $this->optionIdList($options, 'extension');
        $disable = $this->optionIdList($options, 'without');

        if ($enable !== []) {
            $enable = array_values(array_unique([...$base->enableExtensions, ...$enable]));
        }
        if ($disable !== []) {
            $disable = array_values(array_unique([...$base->disableExtensions, ...$disable]));
        }

        return $base->withCliOverrides(
            editors: $editors,
            force: isset($options['force']) ? true : null,
            symlink: isset($options['symlink']) ? true : null,
            prune: isset($options['prune']) ? true : null,
            dryRun: isset($options['dry-run']) ? true : null,
            verbose: isset($options['verbose']) ? true : null,
            enableExtensions: $enable !== [] ? $enable : null,
            disableExtensions: $disable !== [] ? $disable : null,
        );
    }

    /**
     * @param array<string, mixed> $options
     * @return list<string>
     */
    private function optionIdList(array $options, string $key): array
    {
        if (!isset($options[$key])) {
            return [];
        }

        $raw = $options[$key];
        if (is_array($raw)) {
            $parts = $raw;
        } else {
            $parts = explode(',', (string) $raw);
        }

        $ids = [];
        foreach ($parts as $part) {
            if (!is_string($part)) {
                continue;
            }
            $part = trim($part);
            if ($part !== '') {
                $ids[] = $part;
            }
        }

        return $ids;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function projectRoot(array $options): string
    {
        if (isset($options['project']) && is_string($options['project'])) {
            return $this->rootLocator->locate($options['project']);
        }

        return $this->rootLocator->locate();
    }

    /**
     * @param list<string> $args
     * @return array<string, mixed>
     */
    private function parseOptions(array $args): array
    {
        $options = [];
        foreach ($args as $arg) {
            if (!str_starts_with($arg, '--')) {
                continue;
            }
            $arg = substr($arg, 2);
            if (str_contains($arg, '=')) {
                [$key, $value] = explode('=', $arg, 2);
                if (in_array($key, ['extension', 'without'], true)) {
                    $existing = $options[$key] ?? [];
                    if (!is_array($existing)) {
                        $existing = [(string) $existing];
                    }
                    $existing[] = $value;
                    $options[$key] = $existing;
                } else {
                    $options[$key] = $value;
                }
            } else {
                $options[$arg] = true;
            }
        }

        return $options;
    }
}
