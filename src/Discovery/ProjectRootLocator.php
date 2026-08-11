<?php

declare(strict_types=1);

namespace CakePhpAgent\Discovery;

use RuntimeException;

final class ProjectRootLocator
{
    /**
     * Locate the nearest project root containing composer.json, walking upward
     * from $startDirectory (default: cwd).
     *
     * @param string|null $ceiling Optional absolute path the search must not leave.
     */
    public function locate(?string $startDirectory = null, ?string $ceiling = null): string
    {
        $directory = $this->normalize($startDirectory ?? (getcwd() ?: throw new RuntimeException('Unable to determine current working directory.')));
        $ceilingReal = $ceiling !== null ? $this->normalize($ceiling) : null;

        while (true) {
            $composer = $directory . DIRECTORY_SEPARATOR . 'composer.json';
            if (is_file($composer)) {
                return $directory;
            }

            if ($ceilingReal !== null && $directory === $ceilingReal) {
                throw new RuntimeException(sprintf(
                    'Unable to locate a Composer project root starting from "%s" (ceiling: "%s").',
                    $startDirectory ?? (getcwd() ?: '.'),
                    $ceilingReal
                ));
            }

            $parent = dirname($directory);
            if ($parent === $directory) {
                throw new RuntimeException(sprintf(
                    'Unable to locate a Composer project root starting from "%s".',
                    $startDirectory ?? (getcwd() ?: '.')
                ));
            }

            $directory = $parent;
        }
    }

    private function normalize(string $path): string
    {
        $real = realpath($path);
        if ($real === false) {
            throw new RuntimeException(sprintf('Directory does not exist: "%s".', $path));
        }

        return $real;
    }
}
