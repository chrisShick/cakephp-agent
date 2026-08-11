<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use RuntimeException;

final class ExtensionRegistry
{
    /** @var array<string, Extension> */
    private array $extensions = [];

    private bool $loaded = false;

    public function __construct(
        private readonly ManifestLoader $loader = new ManifestLoader(),
        private readonly string $packageRoot = '',
    ) {
    }

    /**
     * @return list<Extension>
     */
    public function all(): array
    {
        $this->ensureLoaded();

        return array_values($this->extensions);
    }

    public function get(string $id): Extension
    {
        $this->ensureLoaded();
        if (!isset($this->extensions[$id])) {
            throw new RuntimeException(sprintf('Unknown extension "%s".', $id));
        }

        return $this->extensions[$id];
    }

    public function has(string $id): bool
    {
        $this->ensureLoaded();

        return isset($this->extensions[$id]);
    }

    /**
     * @return list<string>
     */
    public function ids(): array
    {
        $this->ensureLoaded();

        return array_keys($this->extensions);
    }

    private function ensureLoaded(): void
    {
        if ($this->loaded) {
            return;
        }

        $root = $this->packageRoot !== '' ? $this->packageRoot : PackagePaths::root();
        foreach ($this->loader->loadAll($root) as $extension) {
            $id = $extension->id();
            if (isset($this->extensions[$id])) {
                throw new RuntimeException(sprintf('Duplicate extension id "%s".', $id));
            }
            $this->extensions[$id] = $extension;
        }

        $this->loaded = true;
    }
}
