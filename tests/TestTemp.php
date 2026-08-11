<?php

declare(strict_types=1);

namespace CakePhpAgent\Test;

final class TestTemp
{
    public static function dir(string $prefix): string
    {
        $root = dirname(__DIR__) . '/.phpunit-tmp';
        if (!is_dir($root) && !mkdir($root, 0775, true) && !is_dir($root)) {
            throw new \RuntimeException('Unable to create .phpunit-tmp');
        }

        $dir = $root . '/' . $prefix . '-' . uniqid('', true);
        if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Unable to create "%s".', $dir));
        }

        return $dir;
    }

    public static function removeTree(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }

        rmdir($path);
    }
}
