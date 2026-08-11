<?php

declare(strict_types=1);

namespace CakePhpAgent\Validation;

use CakePhpAgent\Filesystem\Filesystem;
use CakePhpAgent\PackagePaths;

/**
 * Validate canonical knowledge units, evaluations, and CakePHP rule hygiene.
 */
final class ContentValidator
{
    /** @var list<string> */
    private const LARAVEL_TERMS = [
        'Eloquent',
        'FormRequest',
        'ServiceProvider',
        'artisan',
        'Blade',
        'Livewire',
        'Illuminate\\',
    ];

    /** @var list<string> */
    private const REQUIRED_DECISION_SECTIONS = [
        '## Use cases',
        '## Decision questions',
        '## Recommended outcome',
        '## Rejected alternatives',
        '## Exceptions',
        '## Examples',
        '## Evaluations',
    ];

    /** @var list<string> */
    private const TRUTH_LEVELS = [
        'FRAMEWORK_REQUIREMENT',
        'FRAMEWORK_DEFAULT',
        'PLUGIN_SEMANTIC',
        'PACKAGE_RECOMMENDATION',
        'PROJECT_CONVENTION',
        'OPTIONAL_ALTERNATIVE',
    ];

    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly string $packageRoot = '',
    ) {
    }

    /**
     * @return list<string>
     */
    public function validate(): array
    {
        $root = $this->packageRoot !== '' ? $this->packageRoot : PackagePaths::root();
        $errors = [];

        $errors = array_merge($errors, $this->validateDecisions($root));
        $errors = array_merge($errors, $this->validateAntiPatterns($root));
        $errors = array_merge($errors, $this->validateEvaluations($root));
        $errors = array_merge($errors, $this->validateCakephpRules($root));

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateDecisions(string $root): array
    {
        $dir = $root . '/knowledge/decisions';
        if (!$this->filesystem->isDir($dir)) {
            return ['Missing knowledge/decisions directory.'];
        }

        $errors = [];
        $ids = [];
        foreach ($this->filesystem->listFilesRelative($dir) as $relative) {
            if (!str_ends_with($relative, '.md')) {
                continue;
            }
            $path = $dir . '/' . $relative;
            $contents = $this->filesystem->read($path);
            $frontmatter = $this->parseFrontmatter($contents);
            if ($frontmatter === null) {
                $errors[] = sprintf('%s: missing YAML frontmatter.', $relative);
                continue;
            }

            foreach (['id', 'type', 'truth_level'] as $required) {
                if (!isset($frontmatter[$required]) || !is_string($frontmatter[$required]) || $frontmatter[$required] === '') {
                    $errors[] = sprintf('%s: missing frontmatter field "%s".', $relative, $required);
                }
            }

            if (($frontmatter['type'] ?? null) !== 'decision') {
                $errors[] = sprintf('%s: type must be "decision".', $relative);
            }

            $truth = $frontmatter['truth_level'] ?? null;
            if (is_string($truth) && !in_array($truth, self::TRUTH_LEVELS, true)) {
                $errors[] = sprintf('%s: invalid truth_level "%s".', $relative, $truth);
            }

            $id = $frontmatter['id'] ?? null;
            if (is_string($id) && $id !== '') {
                if (isset($ids[$id])) {
                    $errors[] = sprintf('Duplicate knowledge id "%s".', $id);
                }
                $ids[$id] = true;
            }

            foreach (self::REQUIRED_DECISION_SECTIONS as $section) {
                if (!str_contains($contents, $section)) {
                    $errors[] = sprintf('%s: missing section "%s".', $relative, $section);
                }
            }
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateAntiPatterns(string $root): array
    {
        $dir = $root . '/knowledge/anti-patterns';
        if (!$this->filesystem->isDir($dir)) {
            return ['Missing knowledge/anti-patterns directory.'];
        }

        $errors = [];
        foreach ($this->filesystem->listFilesRelative($dir) as $relative) {
            if (!str_ends_with($relative, '.md') || str_ends_with($relative, '.gitkeep')) {
                continue;
            }
            $contents = $this->filesystem->read($dir . '/' . $relative);
            $frontmatter = $this->parseFrontmatter($contents);
            if ($frontmatter === null) {
                $errors[] = sprintf('anti-patterns/%s: missing frontmatter.', $relative);
                continue;
            }
            if (($frontmatter['type'] ?? null) !== 'anti-pattern') {
                $errors[] = sprintf('anti-patterns/%s: type must be "anti-pattern".', $relative);
            }
            foreach (['## Symptoms', '## Why it matters', '## Preferred refactoring'] as $section) {
                if (!str_contains($contents, $section)) {
                    $errors[] = sprintf('anti-patterns/%s: missing section "%s".', $relative, $section);
                }
            }
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateEvaluations(string $root): array
    {
        $dir = $root . '/evaluations';
        if (!$this->filesystem->isDir($dir)) {
            return ['Missing evaluations directory.'];
        }

        $errors = [];
        $ids = [];
        $count = 0;

        foreach ($this->filesystem->listFilesRelative($dir) as $relative) {
            if (!str_ends_with($relative, '.json')) {
                continue;
            }
            $path = $dir . '/' . $relative;
            try {
                $data = json_decode($this->filesystem->read($path), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $errors[] = sprintf('evaluations/%s: invalid JSON (%s).', $relative, $e->getMessage());
                continue;
            }

            if (!is_array($data)) {
                $errors[] = sprintf('evaluations/%s: must be a JSON object.', $relative);
                continue;
            }

            foreach (['id', 'category', 'prompt', 'expected'] as $required) {
                if (!isset($data[$required])) {
                    $errors[] = sprintf('evaluations/%s: missing "%s".', $relative, $required);
                }
            }

            if (isset($data['id']) && is_string($data['id'])) {
                if (isset($ids[$data['id']])) {
                    $errors[] = sprintf('Duplicate evaluation id "%s".', $data['id']);
                }
                $ids[$data['id']] = true;
            }

            if (isset($data['expected']) && !is_array($data['expected'])) {
                $errors[] = sprintf('evaluations/%s: expected must be an object.', $relative);
            }

            $count++;
        }

        if ($count < 10) {
            $errors[] = sprintf('Expected at least 10 evaluation fixtures, found %d.', $count);
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function validateCakephpRules(string $root): array
    {
        $dir = $root . '/rules/cakephp';
        if (!$this->filesystem->isDir($dir)) {
            return ['Missing rules/cakephp directory.'];
        }

        $errors = [];
        foreach ($this->filesystem->listFilesRelative($dir) as $relative) {
            if (!str_ends_with($relative, '.mdc')) {
                continue;
            }
            $path = $dir . '/' . $relative;
            $contents = $this->filesystem->read($path);
            $frontmatter = $this->parseFrontmatter($contents);
            if ($frontmatter === null) {
                $errors[] = sprintf('rules/cakephp/%s: missing frontmatter.', $relative);
                continue;
            }

            if (!isset($frontmatter['truth_level']) || !is_string($frontmatter['truth_level'])) {
                $errors[] = sprintf('rules/cakephp/%s: missing truth_level.', $relative);
            } elseif (!in_array($frontmatter['truth_level'], self::TRUTH_LEVELS, true)) {
                $errors[] = sprintf('rules/cakephp/%s: invalid truth_level.', $relative);
            }

            if (!isset($frontmatter['priority']) || !is_string($frontmatter['priority'])) {
                $errors[] = sprintf('rules/cakephp/%s: missing priority.', $relative);
            }

            // Allow Laravel terms only in files that explicitly discuss anti-hallucination.
            $body = $this->stripFrontmatter($contents);
            $allowsComparison = str_contains(strtolower($body), 'anti-laravel')
                || str_contains(strtolower($body), 'do not invent laravel')
                || str_contains(strtolower($body), 'not laravel');

            if (!$allowsComparison) {
                foreach (self::LARAVEL_TERMS as $term) {
                    if (str_contains($body, $term)) {
                        $errors[] = sprintf(
                            'rules/cakephp/%s: unexpected Laravel term "%s" (use anti-Laravel framing if intentional).',
                            $relative,
                            $term
                        );
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Minimal YAML-ish frontmatter parser for simple key: value and key: [list] forms.
     *
     * @return array<string, mixed>|null
     */
    private function parseFrontmatter(string $contents): ?array
    {
        if (!str_starts_with($contents, "---\n") && !str_starts_with($contents, "---\r\n")) {
            return null;
        }

        $end = strpos($contents, "\n---", 4);
        if ($end === false) {
            return null;
        }

        $yaml = substr($contents, 4, $end - 4);
        $result = [];
        $lines = preg_split('/\R/', $yaml) ?: [];
        $currentListKey = null;

        foreach ($lines as $line) {
            if (preg_match('/^([A-Za-z0-9_]+):\s*(.*)$/', $line, $m)) {
                $currentListKey = null;
                $key = $m[1];
                $value = trim($m[2]);
                if ($value === '' || $value === '|' || $value === '>') {
                    $result[$key] = [];
                    $currentListKey = $key;
                    continue;
                }
                if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
                    $inner = trim($value, '[]');
                    if ($inner === '') {
                        $result[$key] = [];
                    } else {
                        $result[$key] = array_map(
                            static fn (string $v): string => trim($v, " \t\"'"),
                            explode(',', $inner)
                        );
                    }
                    continue;
                }
                if ($value === 'true' || $value === 'false') {
                    $result[$key] = $value === 'true';
                    continue;
                }
                $result[$key] = trim($value, "\"'");
                continue;
            }

            if ($currentListKey !== null && preg_match('/^\s*-\s*(.+)$/', $line, $m)) {
                /** @var list<mixed> $list */
                $list = is_array($result[$currentListKey]) ? $result[$currentListKey] : [];
                $list[] = trim($m[1], "\"'");
                $result[$currentListKey] = $list;
            }
        }

        return $result;
    }

    private function stripFrontmatter(string $contents): string
    {
        if (!str_starts_with($contents, "---\n") && !str_starts_with($contents, "---\r\n")) {
            return $contents;
        }
        $end = strpos($contents, "\n---", 4);
        if ($end === false) {
            return $contents;
        }

        return substr($contents, $end + 4);
    }
}
