<?php

declare(strict_types=1);

namespace CakePhpAgent\Filesystem;

use RuntimeException;

final class Filesystem
{
    public function exists(string $path): bool
    {
        return file_exists($path) || is_link($path);
    }

    public function isFile(string $path): bool
    {
        return is_file($path);
    }

    public function isDir(string $path): bool
    {
        return is_dir($path);
    }

    public function isLink(string $path): bool
    {
        return is_link($path);
    }

    public function mkdir(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        if (!mkdir($path, 0775, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Unable to create directory "%s".', $path));
        }
    }

    public function read(string $path): string
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to read "%s".', $path));
        }

        return $contents;
    }

    public function write(string $path, string $contents): void
    {
        $this->mkdir(dirname($path));
        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException(sprintf('Unable to write "%s".', $path));
        }
    }

    public function copy(string $from, string $to): void
    {
        $this->mkdir(dirname($to));
        if (!copy($from, $to)) {
            throw new RuntimeException(sprintf('Unable to copy "%s" to "%s".', $from, $to));
        }
    }

    public function symlink(string $target, string $link): void
    {
        $this->mkdir(dirname($link));
        if ($this->exists($link)) {
            $this->remove($link);
        }

        if (!symlink($target, $link)) {
            throw new RuntimeException(sprintf('Unable to symlink "%s" -> "%s".', $link, $target));
        }
    }

    public function remove(string $path): void
    {
        if (is_link($path) || is_file($path)) {
            if (!unlink($path)) {
                throw new RuntimeException(sprintf('Unable to remove "%s".', $path));
            }

            return;
        }

        if (!is_dir($path)) {
            return;
        }

        $items = scandir($path);
        if ($items === false) {
            throw new RuntimeException(sprintf('Unable to scan "%s".', $path));
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $this->remove($path . DIRECTORY_SEPARATOR . $item);
        }

        if (!rmdir($path)) {
            throw new RuntimeException(sprintf('Unable to remove directory "%s".', $path));
        }
    }

    public function hashFile(string $path): string
    {
        $hash = hash_file('sha256', $path);
        if ($hash === false) {
            throw new RuntimeException(sprintf('Unable to hash "%s".', $path));
        }

        return $hash;
    }

    /**
     * Recursively list relative file paths under $directory.
     *
     * @return list<string>
     */
    public function listFilesRelative(string $directory): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!$file->isFile() && !$file->isLink()) {
                continue;
            }
            $full = $file->getPathname();
            $relative = substr($full, strlen($directory) + 1);
            $files[] = str_replace('\\', '/', $relative);
        }

        sort($files);

        return $files;
    }

    /**
     * Ensure $path stays inside $root (prevents path traversal).
     */
    public function assertInside(string $root, string $path): string
    {
        $rootReal = realpath($root);
        if ($rootReal === false) {
            throw new RuntimeException(sprintf('Root path does not exist: "%s".', $root));
        }

        $candidate = $path;
        if (!str_starts_with($candidate, DIRECTORY_SEPARATOR) && !preg_match('#^[A-Za-z]:[/\\\\]#', $candidate)) {
            $candidate = $rootReal . DIRECTORY_SEPARATOR . $path;
        }

        $parent = dirname($candidate);
        $parentReal = realpath($parent);
        $baseName = basename($candidate);

        if ($parentReal === false) {
            // Parent may not exist yet during dry planning; normalize without realpath of full path.
            $normalizedRoot = rtrim(str_replace('\\', '/', $rootReal), '/');
            $normalizedCandidate = str_replace('\\', '/', $candidate);
            if (!str_starts_with($normalizedCandidate, $normalizedRoot . '/') && $normalizedCandidate !== $normalizedRoot) {
                throw new RuntimeException(sprintf('Path "%s" escapes root "%s".', $path, $root));
            }

            return $candidate;
        }

        $resolved = $parentReal . DIRECTORY_SEPARATOR . $baseName;
        $normalizedRoot = rtrim(str_replace('\\', '/', $rootReal), '/');
        $normalizedResolved = str_replace('\\', '/', $resolved);

        if (!str_starts_with($normalizedResolved, $normalizedRoot . '/') && $normalizedResolved !== $normalizedRoot) {
            throw new RuntimeException(sprintf('Path "%s" escapes root "%s".', $path, $root));
        }

        return $resolved;
    }
}
