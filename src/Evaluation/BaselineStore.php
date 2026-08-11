<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

use CakePhpAgent\Filesystem\Filesystem;
use JsonException;
use RuntimeException;

/**
 * Read/write evaluation baseline documents for regression comparison.
 */
final class BaselineStore
{
    public function __construct(
        private readonly Filesystem $filesystem = new Filesystem(),
    ) {
    }

    /**
     * @param list<EvaluationCase> $cases
     * @param list<ScoreResult> $results
     * @return array<string, mixed>
     */
    public function build(
        array $cases,
        array $results,
        string $knowledgeVersion,
        string $model,
        string $modelVersion,
    ): array {
        $passed = 0;
        $failed = 0;
        $skipped = 0;
        $serialized = [];

        foreach ($results as $result) {
            match ($result->status) {
                ScoreResult::PASS => $passed++,
                ScoreResult::FAIL => $failed++,
                default => $skipped++,
            };
            $serialized[] = [
                'id' => $result->evaluationId,
                'status' => $result->status,
                'score' => $result->score,
                'notes' => $result->notes,
            ];
        }

        return [
            'schema_version' => 1,
            'knowledge_version' => $knowledgeVersion,
            'model' => $model,
            'model_version' => $modelVersion,
            'created_at' => gmdate('c'),
            'catalog' => [
                'count' => count($cases),
                'fingerprint' => EvaluationCatalog::fingerprint($cases),
                'by_category' => EvaluationCatalog::countByCategory($cases),
                'by_type' => EvaluationCatalog::countByType($cases),
                'ids' => array_map(static fn (EvaluationCase $c): string => $c->id, $cases),
            ],
            'summary' => [
                'total' => count($results),
                'passed' => $passed,
                'failed' => $failed,
                'skipped' => $skipped,
            ],
            'results' => $serialized,
        ];
    }

    /**
     * @param array<string, mixed> $baseline
     */
    public function write(string $path, array $baseline): void
    {
        $json = json_encode($baseline, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new RuntimeException('Unable to encode baseline JSON.');
        }

        $this->filesystem->write($path, $json . "\n");
    }

    /**
     * @return array<string, mixed>
     */
    public function read(string $path): array
    {
        if (!$this->filesystem->isFile($path)) {
            throw new RuntimeException(sprintf('Baseline not found: %s', $path));
        }

        try {
            /** @var mixed $data */
            $data = json_decode($this->filesystem->read($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException(sprintf('Invalid baseline JSON: %s', $e->getMessage()), 0, $e);
        }

        if (!is_array($data)) {
            throw new RuntimeException('Baseline must be a JSON object.');
        }

        return $data;
    }

    /**
     * Compare current catalog/results to a stored baseline.
     *
     * @param list<EvaluationCase> $cases
     * @param list<ScoreResult> $results
     * @param array<string, mixed> $baseline
     * @return array{regressions: list<string>, changes: list<string>, ok: bool}
     */
    public function compare(array $cases, array $results, array $baseline): array
    {
        $changes = [];
        $regressions = [];

        $baselineCatalog = is_array($baseline['catalog'] ?? null) ? $baseline['catalog'] : [];
        $baselineFingerprint = (string) ($baselineCatalog['fingerprint'] ?? '');
        $currentFingerprint = EvaluationCatalog::fingerprint($cases);

        if ($baselineFingerprint !== '' && $baselineFingerprint !== $currentFingerprint) {
            $changes[] = 'catalog fingerprint changed';
        }

        $baselineIds = [];
        if (isset($baselineCatalog['ids']) && is_array($baselineCatalog['ids'])) {
            foreach ($baselineCatalog['ids'] as $id) {
                if (is_string($id)) {
                    $baselineIds[$id] = true;
                }
            }
        }

        $currentIds = [];
        foreach ($cases as $case) {
            $currentIds[$case->id] = true;
            if ($baselineIds !== [] && !isset($baselineIds[$case->id])) {
                $changes[] = sprintf('added evaluation: %s', $case->id);
            }
        }

        foreach (array_keys($baselineIds) as $id) {
            if (!isset($currentIds[$id])) {
                $changes[] = sprintf('removed evaluation: %s', $id);
                $regressions[] = sprintf('missing evaluation id from corpus: %s', $id);
            }
        }

        $baselineResults = [];
        if (isset($baseline['results']) && is_array($baseline['results'])) {
            foreach ($baseline['results'] as $row) {
                if (!is_array($row) || !isset($row['id']) || !is_string($row['id'])) {
                    continue;
                }
                $baselineResults[$row['id']] = (string) ($row['status'] ?? '');
            }
        }

        foreach ($results as $result) {
            $previous = $baselineResults[$result->evaluationId] ?? null;
            if ($previous === ScoreResult::PASS && $result->status === ScoreResult::FAIL) {
                $regressions[] = sprintf('score regression: %s (pass → fail)', $result->evaluationId);
            }
        }

        return [
            'ok' => $regressions === [],
            'regressions' => $regressions,
            'changes' => $changes,
        ];
    }
}
