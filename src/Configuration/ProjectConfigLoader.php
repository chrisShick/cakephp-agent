<?php

declare(strict_types=1);

namespace CakePhpAgent\Configuration;

use CakePhpAgent\Discovery\ComposerMetadataReader;
use RuntimeException;

final class ProjectConfigLoader
{
    public function __construct(
        private readonly ComposerMetadataReader $composerReader = new ComposerMetadataReader(),
    ) {
    }

    public function load(string $projectRoot): ProjectConfig
    {
        $fromComposer = $this->loadFromComposerExtra($projectRoot);
        $fromFile = $this->loadFromConfigFile($projectRoot);

        $merged = array_replace_recursive($fromComposer, $fromFile);

        return new ProjectConfig(
            projectRoot: $projectRoot,
            editors: $this->normalizeEditors($merged['editor'] ?? $merged['editors'] ?? ['cursor']),
            autoInstall: (bool) ($merged['auto-install'] ?? $merged['autoInstall'] ?? false),
            enableExtensions: $this->stringList($merged['extensions']['enable'] ?? []),
            disableExtensions: $this->stringList($merged['extensions']['disable'] ?? []),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFromComposerExtra(string $projectRoot): array
    {
        $json = $this->composerReader->readJson($projectRoot);
        $extra = $json['extra']['cakephp-agent'] ?? [];

        return is_array($extra) ? $extra : [];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFromConfigFile(string $projectRoot): array
    {
        $path = $projectRoot . DIRECTORY_SEPARATOR . ProjectConfig::CONFIG_FILENAME;
        if (!is_file($path)) {
            return [];
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to read "%s".', $path));
        }

        try {
            $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException(sprintf('Invalid JSON in "%s": %s', $path, $e->getMessage()), 0, $e);
        }

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param mixed $value
     * @return list<string>
     */
    private function normalizeEditors(mixed $value): array
    {
        if (is_string($value)) {
            if ($value === 'all') {
                return ['cursor', 'claude', 'codex'];
            }

            return [$value];
        }

        if (!is_array($value)) {
            return ['cursor'];
        }

        $editors = [];
        foreach ($value as $editor) {
            if (is_string($editor) && $editor !== '') {
                $editors[] = $editor;
            }
        }

        return $editors !== [] ? array_values(array_unique($editors)) : ['cursor'];
    }

    /**
     * @param mixed $value
     * @return list<string>
     */
    private function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $list = [];
        foreach ($value as $item) {
            if (is_string($item) && $item !== '') {
                $list[] = $item;
            }
        }

        return $list;
    }
}
