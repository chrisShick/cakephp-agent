<?php

declare(strict_types=1);

namespace CakePhpAgent\Installer;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Filesystem\Filesystem;
use RuntimeException;

final class InstallationStateStore
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
    ) {
    }

    public function path(string $projectRoot): string
    {
        return $projectRoot . DIRECTORY_SEPARATOR . ProjectConfig::LOCK_FILENAME;
    }

    public function load(string $projectRoot): InstallationState
    {
        $path = $this->path($projectRoot);
        if (!$this->filesystem->isFile($path)) {
            return InstallationState::empty('0.0.0');
        }

        try {
            $decoded = json_decode($this->filesystem->read($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException(sprintf('Invalid installation state in "%s": %s', $path, $e->getMessage()), 0, $e);
        }

        if (!is_array($decoded)) {
            throw new RuntimeException(sprintf('Installation state must be a JSON object: "%s".', $path));
        }

        return InstallationState::fromArray($decoded);
    }

    public function save(string $projectRoot, InstallationState $state): void
    {
        $json = json_encode($state->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $this->filesystem->write($this->path($projectRoot), $json . "\n");
    }
}
