<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * One curated behavioral evaluation fixture.
 */
final class EvaluationCase
{
    /**
     * @param list<string> $concepts
     * @param list<string> $preferred
     * @param list<string> $mustNot
     * @param list<string> $relatedKnowledge
     * @param list<string> $requiredExtensions
     */
    public function __construct(
        public readonly string $id,
        public readonly string $category,
        public readonly string $type,
        public readonly string $prompt,
        public readonly array $concepts,
        public readonly array $preferred,
        public readonly array $mustNot,
        public readonly array $relatedKnowledge,
        public readonly array $requiredExtensions,
        public readonly string $relativePath,
        public readonly string $contentHash,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data, string $relativePath, string $rawJson): self
    {
        $expected = $data['expected'] ?? [];
        if (!is_array($expected)) {
            $expected = [];
        }

        $requires = $data['requires'] ?? [];
        $extensions = [];
        if (is_array($requires) && isset($requires['extensions']) && is_array($requires['extensions'])) {
            foreach ($requires['extensions'] as $ext) {
                if (is_string($ext) && $ext !== '') {
                    $extensions[] = $ext;
                }
            }
        }

        return new self(
            id: (string) ($data['id'] ?? ''),
            category: (string) ($data['category'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            prompt: (string) ($data['prompt'] ?? ''),
            concepts: self::stringList($expected['concepts'] ?? []),
            preferred: self::stringList($expected['preferred'] ?? []),
            mustNot: self::stringList($data['must_not'] ?? []),
            relatedKnowledge: self::stringList($data['related_knowledge'] ?? []),
            requiredExtensions: $extensions,
            relativePath: $relativePath,
            contentHash: hash('sha256', $rawJson),
        );
    }

    /**
     * @return list<string>
     */
    private static function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $out = [];
        foreach ($value as $item) {
            if (is_string($item) && $item !== '') {
                $out[] = $item;
            }
        }

        return $out;
    }
}
