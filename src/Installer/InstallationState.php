<?php

declare(strict_types=1);

namespace CakePhpAgent\Installer;

/**
 * Tracks package-managed files so prune never deletes user-authored content.
 */
final class InstallationState
{
    public const FORMAT_VERSION = 1;

    /**
     * @param array<string, array{hash: string, editor: string, kind: string}> $files
     * @param list<string> $editors
     * @param list<string> $extensions
     */
    public function __construct(
        public readonly int $formatVersion,
        public readonly string $packageVersion,
        public readonly array $editors,
        public readonly array $extensions,
        public readonly array $files,
        public readonly string $installedAt,
    ) {
    }

    public static function empty(string $packageVersion): self
    {
        return new self(
            formatVersion: self::FORMAT_VERSION,
            packageVersion: $packageVersion,
            editors: [],
            extensions: [],
            files: [],
            installedAt: gmdate('c'),
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $files = [];
        foreach ($data['files'] ?? [] as $relative => $meta) {
            if (!is_string($relative) || !is_array($meta)) {
                continue;
            }
            $files[$relative] = [
                'hash' => (string) ($meta['hash'] ?? ''),
                'editor' => (string) ($meta['editor'] ?? ''),
                'kind' => (string) ($meta['kind'] ?? 'rule'),
            ];
        }

        return new self(
            formatVersion: (int) ($data['formatVersion'] ?? self::FORMAT_VERSION),
            packageVersion: (string) ($data['packageVersion'] ?? '0.0.0'),
            editors: array_values(array_filter(
                is_array($data['editors'] ?? null) ? $data['editors'] : [],
                static fn (mixed $v): bool => is_string($v)
            )),
            extensions: array_values(array_filter(
                is_array($data['extensions'] ?? null) ? $data['extensions'] : [],
                static fn (mixed $v): bool => is_string($v)
            )),
            files: $files,
            installedAt: (string) ($data['installedAt'] ?? gmdate('c')),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'formatVersion' => $this->formatVersion,
            'packageVersion' => $this->packageVersion,
            'editors' => $this->editors,
            'extensions' => $this->extensions,
            'files' => $this->files,
            'installedAt' => $this->installedAt,
        ];
    }

    /**
     * @param array<string, array{hash: string, editor: string, kind: string}> $files
     * @param list<string> $editors
     * @param list<string> $extensions
     */
    public function withInstallResult(
        string $packageVersion,
        array $editors,
        array $extensions,
        array $files,
    ): self {
        return new self(
            formatVersion: self::FORMAT_VERSION,
            packageVersion: $packageVersion,
            editors: $editors,
            extensions: $extensions,
            files: $files,
            installedAt: gmdate('c'),
        );
    }
}
