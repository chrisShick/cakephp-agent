<?php

declare(strict_types=1);

namespace CakePhpAgent\Command;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Configuration\ProjectConfigLoader;
use CakePhpAgent\Discovery\ComposerMetadataReader;
use CakePhpAgent\Discovery\ProjectRootLocator;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Installer\InstallAction;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\PackagePaths;
use Throwable;

final class Application
{
    public function __construct(
        private readonly ProjectRootLocator $rootLocator = new ProjectRootLocator(),
        private readonly ProjectConfigLoader $configLoader = new ProjectConfigLoader(),
        private readonly ComposerMetadataReader $composerReader = new ComposerMetadataReader(),
        private readonly EditorRegistry $editorRegistry = new EditorRegistry(),
        private readonly KnowledgeInstaller $installer = new KnowledgeInstaller(),
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
  install    Install rules/skills into editor target directories
  detect     Show detected project / Composer capabilities (Phase 1: basic)
  validate   Validate package content layout
  doctor     Sanity-check project + package
  version    Show package version
  help       Show this help

Install options:
  --editor=cursor|claude|codex|all   Target editor(s) (default: cursor)
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

        echo 'CakePHP Agent' . PHP_EOL . PHP_EOL;
        echo 'Project:' . PHP_EOL;
        echo '  ' . $config->projectRoot . PHP_EOL . PHP_EOL;
        echo 'Editors: ' . implode(', ', $config->editors) . PHP_EOL;
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
        $projectRoot = $this->projectRoot($options);
        $packages = $this->composerReader->installedPackages($projectRoot);

        echo 'Project: ' . $projectRoot . PHP_EOL . PHP_EOL;
        echo 'Composer packages (resolved or constrained):' . PHP_EOL;

        $interesting = [
            'cakephp/cakephp',
            'cakephp/authentication',
            'cakephp/authorization',
            'friendsofcake/crud',
            'friendsofcake/search',
        ];

        foreach ($interesting as $name) {
            if (isset($packages[$name])) {
                echo sprintf("  ✓ %s (%s)\n", $name, $packages[$name]);
            } else {
                echo sprintf("  · %s (not present)\n", $name);
            }
        }

        echo PHP_EOL . 'Extension auto-enable lands in Phase 2.' . PHP_EOL;

        return 0;
    }

    private function validate(): int
    {
        $root = PackagePaths::root();
        $errors = [];

        foreach (['rules/engineering', 'rules/php', 'rules/cakephp', 'skills', 'schemas', 'src'] as $rel) {
            if (!is_dir($root . '/' . $rel)) {
                $errors[] = "Missing directory: {$rel}";
            }
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

        return $base->withCliOverrides(
            editors: $editors,
            force: isset($options['force']) ? true : null,
            symlink: isset($options['symlink']) ? true : null,
            prune: isset($options['prune']) ? true : null,
            dryRun: isset($options['dry-run']) ? true : null,
            verbose: isset($options['verbose']) ? true : null,
        );
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
                $options[$key] = $value;
            } else {
                $options[$arg] = true;
            }
        }

        return $options;
    }
}
