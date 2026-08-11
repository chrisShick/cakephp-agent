<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

/**
 * Parsed extension manifest (capability pack metadata).
 */
final class ExtensionManifest
{
    public const TYPE_COMPOSER = 'composer-package';
    public const TYPE_ARCHITECTURE = 'architecture';
    public const TYPE_INFRASTRUCTURE = 'infrastructure';
    public const TYPE_INTEGRATION = 'integration';

    /**
     * @param list<array{package: string, constraint: string|null}> $detectComposer
     * @param array<string, string> $requires
     * @param list<string> $dependsOn
     * @param list<string> $conflictsWith
     * @param list<string> $rules
     * @param list<string> $skills
     * @param list<string> $agents
     * @param list<string> $activateWhenAllExtensionsPresent
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $version,
        public readonly string $type,
        public readonly array $detectComposer = [],
        public readonly array $requires = [],
        public readonly array $dependsOn = [],
        public readonly array $conflictsWith = [],
        public readonly array $rules = [],
        public readonly array $skills = [],
        public readonly array $agents = [],
        public readonly bool $defaultEnabledWhenDetected = true,
        public readonly array $activateWhenAllExtensionsPresent = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $detectComposer = [];
        $detect = $data['detect']['composer'] ?? [];
        if (is_array($detect)) {
            foreach ($detect as $item) {
                if (!is_array($item) || !isset($item['package']) || !is_string($item['package'])) {
                    continue;
                }
                $detectComposer[] = [
                    'package' => $item['package'],
                    'constraint' => isset($item['constraint']) && is_string($item['constraint'])
                        ? $item['constraint']
                        : null,
                ];
            }
        }

        $requires = [];
        if (isset($data['requires']) && is_array($data['requires'])) {
            foreach ($data['requires'] as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $requires[$key] = $value;
                }
            }
        }

        return new self(
            id: (string) ($data['id'] ?? ''),
            name: (string) ($data['name'] ?? ''),
            description: (string) ($data['description'] ?? ''),
            version: (string) ($data['version'] ?? '1.0'),
            type: (string) ($data['type'] ?? self::TYPE_COMPOSER),
            detectComposer: $detectComposer,
            requires: $requires,
            dependsOn: self::stringList($data['dependsOn'] ?? []),
            conflictsWith: self::stringList($data['conflictsWith'] ?? []),
            rules: self::stringList($data['rules'] ?? []),
            skills: self::stringList($data['skills'] ?? []),
            agents: self::stringList($data['agents'] ?? []),
            defaultEnabledWhenDetected: (bool) ($data['defaultEnabledWhenDetected'] ?? true),
            activateWhenAllExtensionsPresent: self::stringList($data['activateWhenAllExtensionsPresent'] ?? []),
        );
    }

    /**
     * @param mixed $value
     * @return list<string>
     */
    private static function stringList(mixed $value): array
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
