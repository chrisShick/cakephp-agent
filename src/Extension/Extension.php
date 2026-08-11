<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

/**
 * A loaded extension pack rooted at a filesystem directory.
 */
final class Extension
{
    public function __construct(
        public readonly ExtensionManifest $manifest,
        public readonly string $rootPath,
    ) {
    }

    public function id(): string
    {
        return $this->manifest->id;
    }

    public function rulesDirectory(): string
    {
        return $this->rootPath . '/rules';
    }

    public function skillsDirectory(): string
    {
        return $this->rootPath . '/skills';
    }

    public function agentsDirectory(): string
    {
        return $this->rootPath . '/agents';
    }
}
