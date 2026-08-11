<?php

declare(strict_types=1);

namespace CakePhpAgent\Installer;

final class InstallAction
{
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const SKIP = 'skip';
    public const PRESERVE = 'preserve';
    public const PRUNE = 'prune';

    public function __construct(
        public readonly string $action,
        public readonly string $relativePath,
        public readonly string $sourcePath,
        public readonly string $targetPath,
        public readonly string $editor,
        public readonly string $kind,
        public readonly string $reason = '',
    ) {
    }
}
