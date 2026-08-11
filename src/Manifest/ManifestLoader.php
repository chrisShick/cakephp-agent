<?php

declare(strict_types=1);

namespace CakePhpAgent\Manifest;

use CakePhpAgent\Extension\Extension;
use CakePhpAgent\Extension\ExtensionManifest;
use CakePhpAgent\Filesystem\Filesystem;
use RuntimeException;

final class ManifestLoader
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly ManifestValidator $validator = new ManifestValidator(),
    ) {
    }

    /**
     * Load all extensions from package extensions/ and integrations/ directories.
     *
     * @return list<Extension>
     */
    public function loadAll(string $packageRoot): array
    {
        $extensions = [];
        foreach (['extensions', 'integrations'] as $dirname) {
            $base = $packageRoot . '/' . $dirname;
            if (!$this->filesystem->isDir($base)) {
                continue;
            }

            $entries = scandir($base);
            if ($entries === false) {
                throw new RuntimeException(sprintf('Unable to scan "%s".', $base));
            }

            foreach ($entries as $entry) {
                if ($entry === '.' || $entry === '..' || str_starts_with($entry, '.')) {
                    continue;
                }
                $dir = $base . '/' . $entry;
                if (!$this->filesystem->isDir($dir)) {
                    continue;
                }
                $manifestPath = $dir . '/manifest.json';
                if (!$this->filesystem->isFile($manifestPath)) {
                    continue;
                }
                $extensions[] = $this->loadOne($dir);
            }
        }

        return $extensions;
    }

    public function loadOne(string $extensionRoot): Extension
    {
        $manifestPath = $extensionRoot . '/manifest.json';
        if (!$this->filesystem->isFile($manifestPath)) {
            throw new RuntimeException(sprintf('Missing manifest.json in "%s".', $extensionRoot));
        }

        try {
            $data = json_decode($this->filesystem->read($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException(sprintf('Invalid JSON in "%s": %s', $manifestPath, $e->getMessage()), 0, $e);
        }

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Manifest must be a JSON object: "%s".', $manifestPath));
        }

        /** @var array<string, mixed> $data */
        $errors = $this->validator->validate($data, $extensionRoot);
        if ($errors !== []) {
            throw new RuntimeException(sprintf(
                "Invalid extension manifest in \"%s\":\n - %s",
                $manifestPath,
                implode("\n - ", $errors)
            ));
        }

        $manifest = ExtensionManifest::fromArray($data);
        $rootReal = realpath($extensionRoot);
        if ($rootReal === false) {
            throw new RuntimeException(sprintf('Unable to resolve extension root "%s".', $extensionRoot));
        }

        return new Extension($manifest, $rootReal);
    }
}
