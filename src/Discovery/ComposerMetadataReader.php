<?php

declare(strict_types=1);

namespace CakePhpAgent\Discovery;

use RuntimeException;

/**
 * Read-only Composer metadata inspection. Never executes Composer scripts.
 */
final class ComposerMetadataReader
{
    /**
     * @return array<string, mixed>
     */
    public function readJson(string $projectRoot): array
    {
        $path = $projectRoot . DIRECTORY_SEPARATOR . 'composer.json';
        if (!is_file($path)) {
            throw new RuntimeException(sprintf('composer.json not found in "%s".', $projectRoot));
        }

        return $this->decode($path);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function readLock(string $projectRoot): ?array
    {
        $path = $projectRoot . DIRECTORY_SEPARATOR . 'composer.lock';
        if (!is_file($path)) {
            return null;
        }

        return $this->decode($path);
    }

    /**
     * Resolved package versions prefer composer.lock; fall back to require constraints.
     *
     * @return array<string, string> package => version or constraint
     */
    public function installedPackages(string $projectRoot): array
    {
        $lock = $this->readLock($projectRoot);
        if ($lock !== null) {
            $packages = [];
            foreach (['packages', 'packages-dev'] as $section) {
                foreach ($lock[$section] ?? [] as $package) {
                    if (!is_array($package) || !isset($package['name'], $package['version'])) {
                        continue;
                    }
                    $packages[(string) $package['name']] = (string) $package['version'];
                }
            }

            return $packages;
        }

        $json = $this->readJson($projectRoot);
        $packages = [];
        foreach (['require', 'require-dev'] as $section) {
            foreach ($json[$section] ?? [] as $name => $constraint) {
                if (!is_string($name) || !is_string($constraint) || $name === 'php') {
                    continue;
                }
                $packages[$name] = $constraint;
            }
        }

        return $packages;
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(string $path): array
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to read "%s".', $path));
        }

        try {
            $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException(sprintf('Invalid JSON in "%s": %s', $path, $e->getMessage()), 0, $e);
        }

        if (!is_array($decoded)) {
            throw new RuntimeException(sprintf('Expected JSON object in "%s".', $path));
        }

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }
}
