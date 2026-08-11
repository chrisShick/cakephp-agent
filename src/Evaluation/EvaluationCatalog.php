<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

use CakePhpAgent\Filesystem\Filesystem;
use CakePhpAgent\PackagePaths;
use JsonException;
use RuntimeException;

/**
 * Deterministic loader for evaluations/*.json (sorted by id).
 */
final class EvaluationCatalog
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
        private readonly ?string $evaluationsRoot = null,
    ) {
    }

    /**
     * @return list<EvaluationCase>
     */
    public function load(?EvaluationFilter $filter = null): array
    {
        $root = $this->evaluationsRoot ?? PackagePaths::evaluations();
        if (!$this->filesystem->isDir($root)) {
            throw new RuntimeException(sprintf('Evaluations directory missing: %s', $root));
        }

        $relativeFiles = $this->filesystem->listFilesRelative($root);
        $cases = [];

        foreach ($relativeFiles as $relative) {
            if (!str_ends_with($relative, '.json')) {
                continue;
            }

            $absolute = $root . '/' . $relative;
            $raw = $this->filesystem->read($absolute);

            try {
                /** @var mixed $data */
                $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                throw new RuntimeException(sprintf('Invalid JSON in evaluations/%s: %s', $relative, $e->getMessage()), 0, $e);
            }

            if (!is_array($data)) {
                throw new RuntimeException(sprintf('evaluations/%s must be a JSON object.', $relative));
            }

            $case = EvaluationCase::fromArray($data, $relative, $raw);
            if ($case->id === '') {
                throw new RuntimeException(sprintf('evaluations/%s missing id.', $relative));
            }

            if (isset($cases[$case->id])) {
                throw new RuntimeException(sprintf('Duplicate evaluation id "%s".', $case->id));
            }

            if ($filter !== null && !$filter->matches($case)) {
                continue;
            }

            $cases[$case->id] = $case;
        }

        ksort($cases, SORT_STRING);

        return array_values($cases);
    }

    /**
     * Stable fingerprint of the filtered catalog for baseline comparison.
     *
     * @param list<EvaluationCase> $cases
     */
    public static function fingerprint(array $cases): string
    {
        $parts = [];
        foreach ($cases as $case) {
            $parts[] = $case->id . ':' . $case->contentHash;
        }

        return hash('sha256', implode("\n", $parts));
    }

    /**
     * @param list<EvaluationCase> $cases
     * @return array<string, int>
     */
    public static function countByCategory(array $cases): array
    {
        $counts = [];
        foreach ($cases as $case) {
            $counts[$case->category] = ($counts[$case->category] ?? 0) + 1;
        }
        ksort($counts);

        return $counts;
    }

    /**
     * @param list<EvaluationCase> $cases
     * @return array<string, int>
     */
    public static function countByType(array $cases): array
    {
        $counts = [];
        foreach ($cases as $case) {
            $key = $case->type !== '' ? $case->type : '(none)';
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }
        ksort($counts);

        return $counts;
    }
}
