<?php

declare(strict_types=1);

namespace CakePhpAgent\Command;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Configuration\ProjectConfigLoader;
use CakePhpAgent\Discovery\ComposerMetadataReader;
use CakePhpAgent\Discovery\ProjectRootLocator;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Evaluation\EvaluationFilter;
use CakePhpAgent\Evaluation\EvaluationRunner;
use CakePhpAgent\Evaluation\ScoreResult;
use CakePhpAgent\Extension\ExtensionDecision;
use CakePhpAgent\Extension\ExtensionRegistry;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Installer\InstallAction;
use CakePhpAgent\Installer\InstallationStateStore;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use CakePhpAgent\Validation\ContentValidator;
use Throwable;

final class Application
{
    public const VERSION = '1.0.0-beta.2';

    public function __construct(
        private readonly ProjectRootLocator $rootLocator = new ProjectRootLocator(),
        private readonly ProjectConfigLoader $configLoader = new ProjectConfigLoader(),
        private readonly ComposerMetadataReader $composerReader = new ComposerMetadataReader(),
        private readonly EditorRegistry $editorRegistry = new EditorRegistry(),
        private readonly KnowledgeInstaller $installer = new KnowledgeInstaller(),
        private readonly ExtensionResolver $extensionResolver = new ExtensionResolver(),
        private readonly ExtensionRegistry $extensionRegistry = new ExtensionRegistry(),
        private readonly ManifestLoader $manifestLoader = new ManifestLoader(),
        private readonly EvaluationRunner $evaluationRunner = new EvaluationRunner(),
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
                'uninstall' => $this->uninstall($options),
                'detect' => $this->detect($options),
                'extensions' => $this->extensions($options),
                'explain' => $this->explain($options),
                'validate' => $this->validate(),
                'eval' => $this->runEval($options),
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
  uninstall    Remove lock-tracked managed editor files
  detect       Show Composer capabilities and enabled extensions
  extensions   List known extension packs and status for a project
  explain      Explain why each extension is enabled or not
  validate     Validate package content, manifests, knowledge, evaluations
  eval         Run evaluation corpus self-check / baselines (offline)
  doctor       Sanity-check project + package install health
  version      Show package version
  help         Show this help

Install options:
  --editor=cursor|claude|codex|all   Target editor(s) (default: cursor)
  --extension=ID                     Force-enable extension (repeatable / comma-separated)
  --without=ID                       Force-disable extension (repeatable / comma-separated)
  --force                            Overwrite managed / conflicting files
  --symlink                          Symlink instead of copy (Unix; see docs)
  --prune                            Remove previously managed files no longer present
  --dry-run                          Show actions without writing
  --verbose                          Verbose output
  --project=PATH                     Project root (default: discover from cwd)

Uninstall options:
  --editor=cursor|claude|codex|all   Limit removal to editor(s) (default: all in lock)
  --dry-run                          Show deletions without removing
  --project=PATH                     Project root
  --verbose                          Show each path

Eval options:
  --category=NAME                    Filter by category (repeatable / comma-separated)
  --type=NAME                        Filter by evaluation type (repeatable / comma-separated)
  --id=ID                            Filter by evaluation id (repeatable / comma-separated)
  --extension=ID                     Prefer fixtures requiring extension (core still included)
  --model=NAME                       Baseline model label (default: self-check)
  --model-version=VER                Baseline model version (default: 1)
  --format=text|json                 Output format (default: text)
  --write-baseline=PATH              Write baseline JSON after the run
  --compare-baseline=PATH            Compare run to a previous baseline

Notes:
  - Codex installs rules/skills only (no agents directory).
  - Offline eval self-check proves fixture/scorer plumbing — not live model quality.
  - Project overlays under .ai/ are never overwritten or uninstalled.

Project-owned overlays live under .ai/ and are never overwritten.

HELP;

        return 0;
    }

    private function version(): int
    {
        echo 'cakephp-agent ' . self::VERSION . PHP_EOL;

        return 0;
    }

    /**
     * Remove lock-tracked managed files for selected editors.
     *
     * @param array<string, mixed> $options
     */
    private function uninstall(array $options): int
    {
        $projectRoot = $this->projectRoot($options);
        $base = $this->configLoader->load($projectRoot);

        $editors = ['cursor', 'claude', 'codex'];
        if (isset($options['editor'])) {
            $editors = $options['editor'] === 'all'
                ? ['cursor', 'claude', 'codex']
                : [(string) $options['editor']];
        }

        $config = $base->withCliOverrides(
            editors: $editors,
            dryRun: isset($options['dry-run']) ? true : null,
            verbose: isset($options['verbose']) ? true : null,
        );

        $result = $this->installer->uninstall($config);
        $actions = $result['actions'];

        echo 'CakePHP Agent uninstall' . PHP_EOL . PHP_EOL;
        echo 'Project:' . PHP_EOL;
        echo '  ' . $config->projectRoot . PHP_EOL;
        if ($config->dryRun) {
            echo 'Mode: dry-run' . PHP_EOL;
        }
        echo PHP_EOL . 'Actions:' . PHP_EOL;

        if ($actions === []) {
            echo '  (nothing to remove — no matching lock entries)' . PHP_EOL;
        }

        foreach ($actions as $action) {
            echo sprintf("  [PRUNE] %s\n", $action->relativePath);
        }

        echo PHP_EOL . sprintf("Summary: %d prune\n", count($actions));
        if ($config->dryRun) {
            echo 'Dry-run complete. No files removed.' . PHP_EOL;
        } elseif ($result['removedLock']) {
            echo 'Lock file removed. .ai/ overlays left untouched.' . PHP_EOL;
        } else {
            echo 'Selected editor files removed; lock updated for remaining editors.' . PHP_EOL;
        }

        return 0;
    }

    /**
     * Offline evaluation platform: load corpus, self-check heuristic scorer, baselines.
     *
     * @param array<string, mixed> $options
     */
    private function runEval(array $options): int
    {
        $filter = new EvaluationFilter(
            categories: $this->optionIdList($options, 'category'),
            types: $this->optionIdList($options, 'type'),
            ids: $this->optionIdList($options, 'id'),
            extensions: $this->optionIdList($options, 'extension'),
        );

        $run = $this->evaluationRunner->run($filter);
        $model = isset($options['model']) && is_string($options['model']) && $options['model'] !== ''
            ? $options['model']
            : 'self-check';
        $modelVersion = isset($options['model-version']) && is_string($options['model-version']) && $options['model-version'] !== ''
            ? $options['model-version']
            : '1';

        $format = isset($options['format']) && is_string($options['format']) ? $options['format'] : 'text';

        if (isset($options['write-baseline']) && is_string($options['write-baseline'])) {
            $document = $this->evaluationRunner->buildBaseline(
                $run['cases'],
                $run['results'],
                self::VERSION,
                $model,
                $modelVersion,
            );
            $this->evaluationRunner->baselineStore()->write($options['write-baseline'], $document);
        }

        $compare = null;
        if (isset($options['compare-baseline']) && is_string($options['compare-baseline'])) {
            $baseline = $this->evaluationRunner->baselineStore()->read($options['compare-baseline']);
            $compare = $this->evaluationRunner->compareBaseline($baseline, $run['cases'], $run['results']);
        }

        if ($format === 'json') {
            $payload = [
                'knowledge_version' => self::VERSION,
                'model' => $model,
                'model_version' => $modelVersion,
                'catalog' => [
                    'count' => count($run['cases']),
                    'fingerprint' => $run['fingerprint'],
                    'by_category' => $run['by_category'],
                    'by_type' => $run['by_type'],
                ],
                'self_check_ok' => $run['self_check_ok'],
                'results' => array_map(
                    static fn (ScoreResult $r): array => [
                        'id' => $r->evaluationId,
                        'status' => $r->status,
                        'score' => $r->score,
                        'notes' => $r->notes,
                    ],
                    $run['results'],
                ),
                'compare' => $compare,
            ];
            echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        } else {
            echo 'CakePHP Agent eval (offline self-check)' . PHP_EOL . PHP_EOL;
            echo sprintf("Knowledge version: %s\n", self::VERSION);
            echo sprintf("Model label: %s (%s)\n", $model, $modelVersion);
            echo sprintf("Fixtures: %d\n", count($run['cases']));
            echo sprintf("Fingerprint: %s\n", $run['fingerprint']);
            echo PHP_EOL . 'By category:' . PHP_EOL;
            foreach ($run['by_category'] as $category => $count) {
                echo sprintf("  %-20s %d\n", $category, $count);
            }
            echo PHP_EOL . 'By type:' . PHP_EOL;
            foreach ($run['by_type'] as $type => $count) {
                echo sprintf("  %-20s %d\n", $type, $count);
            }

            $failed = array_values(array_filter(
                $run['results'],
                static fn (ScoreResult $r): bool => $r->status === ScoreResult::FAIL
            ));
            echo PHP_EOL . sprintf(
                "Self-check: %s (%d fail)\n",
                $run['self_check_ok'] ? 'OK' : 'FAILED',
                count($failed)
            );
            if ($failed !== []) {
                foreach ($failed as $result) {
                    echo sprintf("  ✗ %s\n", $result->evaluationId);
                    foreach ($result->notes as $note) {
                        echo '      - ' . $note . PHP_EOL;
                    }
                }
            }

            if (isset($options['write-baseline']) && is_string($options['write-baseline'])) {
                echo PHP_EOL . 'Wrote baseline: ' . $options['write-baseline'] . PHP_EOL;
            }

            if ($compare !== null) {
                echo PHP_EOL . 'Baseline compare:' . PHP_EOL;
                echo '  ' . ($compare['ok'] ? 'OK (no score regressions)' : 'REGRESSIONS DETECTED') . PHP_EOL;
                foreach ($compare['regressions'] as $line) {
                    echo '  ✗ ' . $line . PHP_EOL;
                }
                foreach ($compare['changes'] as $line) {
                    echo '  · ' . $line . PHP_EOL;
                }
            }
        }

        if (!$run['self_check_ok']) {
            return 1;
        }
        if ($compare !== null && !$compare['ok']) {
            return 1;
        }

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
            echo 'Knowledge, evaluations, skills, agents, and CakePHP rules OK.' . PHP_EOL;
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
        $config = $this->resolveConfig($options);
        $packages = $this->composerReader->installedPackages($projectRoot);
        $resolution = $this->extensionResolver->resolve($config);
        $lockPath = $projectRoot . '/' . ProjectConfig::LOCK_FILENAME;
        $lockPresent = is_file($lockPath);
        $lockCount = 0;
        $lockEditors = [];
        $missingManaged = 0;
        if ($lockPresent) {
            $state = (new InstallationStateStore())->load($projectRoot);
            $lockCount = count($state->files);
            $lockEditors = $state->editors;
            foreach ($state->files as $relative => $_meta) {
                if (!file_exists($projectRoot . '/' . $relative)) {
                    $missingManaged++;
                }
            }
        }

        $contentErrors = (new ContentValidator())->validate();
        $exit = 0;

        echo 'CakePHP Agent doctor' . PHP_EOL . PHP_EOL;
        echo 'Environment' . PHP_EOL;
        echo '  Project root: ' . $projectRoot . PHP_EOL;
        echo '  Package root: ' . PackagePaths::root() . PHP_EOL;
        echo '  Package version: ' . self::VERSION . PHP_EOL;
        echo '  PHP: ' . PHP_VERSION . PHP_EOL;
        echo '  CakePHP: ' . ($packages['cakephp/cakephp'] ?? '(not detected)') . PHP_EOL;
        echo PHP_EOL;

        echo 'Editors' . PHP_EOL;
        echo '  Supported: ' . implode(', ', $this->editorRegistry->ids()) . PHP_EOL;
        echo '  Codex agents: unsupported (rules/skills only)' . PHP_EOL;
        echo PHP_EOL;

        echo 'Extensions' . PHP_EOL;
        echo '  Registered: ' . implode(', ', $this->extensionRegistry->ids()) . PHP_EOL;
        echo '  Enabled for project: ' . (
            $resolution->enabledIds() === [] ? '(none)' : implode(', ', $resolution->enabledIds())
        ) . PHP_EOL;
        echo PHP_EOL;

        echo 'Install state' . PHP_EOL;
        echo '  .ai overlay: ' . (is_dir($projectRoot . '/.ai') ? 'present' : 'not present') . PHP_EOL;
        echo '  Lock file: ' . ($lockPresent ? 'present (' . $lockCount . ' managed files)' : 'not present') . PHP_EOL;
        if ($lockPresent) {
            echo '  Lock editors: ' . ($lockEditors === [] ? '(none)' : implode(', ', $lockEditors)) . PHP_EOL;
            if ($missingManaged > 0) {
                echo '  WARNING: ' . $missingManaged . ' lock-tracked path(s) missing on disk' . PHP_EOL;
                $exit = 1;
            }
        }
        echo PHP_EOL;

        echo 'Package content' . PHP_EOL;
        if ($contentErrors === []) {
            echo '  validate: OK' . PHP_EOL;
        } else {
            echo '  validate: FAILED (' . count($contentErrors) . ' issue(s))' . PHP_EOL;
            foreach (array_slice($contentErrors, 0, 5) as $error) {
                echo '    - ' . $error . PHP_EOL;
            }
            $exit = 1;
        }
        echo PHP_EOL;

        echo 'Suggested next step' . PHP_EOL;
        if (!$lockPresent) {
            echo '  vendor/bin/cakephp-agent install --editor=cursor --dry-run --verbose' . PHP_EOL;
        } elseif ($missingManaged > 0) {
            echo '  vendor/bin/cakephp-agent install --editor=all' . PHP_EOL;
        } else {
            echo '  vendor/bin/cakephp-agent detect' . PHP_EOL;
        }

        return $exit;
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
                if (in_array($key, ['extension', 'without', 'category', 'type', 'id'], true)) {
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
