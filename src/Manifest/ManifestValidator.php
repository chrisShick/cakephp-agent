<?php

declare(strict_types=1);

namespace CakePhpAgent\Manifest;

use CakePhpAgent\Extension\ExtensionManifest;
use CakePhpAgent\Filesystem\Filesystem;
use RuntimeException;

/**
 * Structural validation for extension manifests (schema-aligned).
 */
final class ManifestValidator
{
    /** @var list<string> */
    private const TYPES = [
        ExtensionManifest::TYPE_COMPOSER,
        ExtensionManifest::TYPE_ARCHITECTURE,
        ExtensionManifest::TYPE_INFRASTRUCTURE,
        ExtensionManifest::TYPE_INTEGRATION,
    ];

    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @return list<string> error messages (empty = valid)
     */
    public function validate(array $data, ?string $extensionRoot = null): array
    {
        $errors = [];

        foreach (['id', 'name', 'version', 'type'] as $required) {
            if (!isset($data[$required]) || !is_string($data[$required]) || $data[$required] === '') {
                $errors[] = sprintf('Missing or invalid required field "%s".', $required);
            }
        }

        if (isset($data['type']) && is_string($data['type']) && !in_array($data['type'], self::TYPES, true)) {
            $errors[] = sprintf('Invalid type "%s".', $data['type']);
        }

        if (isset($data['id']) && is_string($data['id']) && !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $data['id'])) {
            $errors[] = sprintf('Extension id "%s" must be kebab-case.', $data['id']);
        }

        if (isset($data['detect']) && is_array($data['detect'])) {
            $composer = $data['detect']['composer'] ?? null;
            if ($composer !== null) {
                if (!is_array($composer)) {
                    $errors[] = 'detect.composer must be an array.';
                } else {
                    foreach ($composer as $i => $item) {
                        if (!is_array($item) || !isset($item['package']) || !is_string($item['package'])) {
                            $errors[] = sprintf('detect.composer[%s] requires a string "package".', (string) $i);
                        }
                    }
                }
            }
        }

        if ($extensionRoot !== null) {
            $errors = array_merge($errors, $this->validatePaths($data, $extensionRoot));
        }

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     * @return list<string>
     */
    private function validatePaths(array $data, string $extensionRoot): array
    {
        $errors = [];
        $rootReal = realpath($extensionRoot);
        if ($rootReal === false) {
            return [sprintf('Extension root does not exist: "%s".', $extensionRoot)];
        }

        foreach (['rules', 'skills', 'agents'] as $section) {
            $patterns = $data[$section] ?? [];
            if (!is_array($patterns)) {
                $errors[] = sprintf('"%s" must be an array of path patterns.', $section);
                continue;
            }

            foreach ($patterns as $pattern) {
                if (!is_string($pattern)) {
                    $errors[] = sprintf('Invalid path pattern in "%s".', $section);
                    continue;
                }

                $pattern = trim($pattern);
                if ($pattern === '') {
                    $errors[] = sprintf('Empty path pattern in "%s".', $section);
                    continue;
                }

                if (str_contains($pattern, '..')) {
                    $errors[] = sprintf('Path traversal not allowed: "%s".', $pattern);
                    continue;
                }

                // Globs like rules/*.mdc are OK if the parent dir exists.
                $literal = str_contains($pattern, '*')
                    ? dirname($pattern)
                    : $pattern;

                if ($literal === '.') {
                    continue;
                }

                try {
                    $this->filesystem->assertInside($rootReal, $literal);
                } catch (RuntimeException $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }

        return $errors;
    }
}
