<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * Offline evaluation runner: load corpus, self-check scorer, optional baselines.
 */
final class EvaluationRunner
{
    public function __construct(
        private readonly EvaluationCatalog $catalog = new EvaluationCatalog(),
        private readonly HeuristicScorer $scorer = new HeuristicScorer(),
        private readonly SelfCheckResponseFactory $responses = new SelfCheckResponseFactory(),
        private readonly BaselineStore $baselines = new BaselineStore(),
    ) {
    }

    /**
     * @return array{
     *   cases: list<EvaluationCase>,
     *   results: list<ScoreResult>,
     *   self_check_ok: bool,
     *   by_category: array<string, int>,
     *   by_type: array<string, int>,
     *   fingerprint: string
     * }
     */
    public function run(EvaluationFilter $filter): array
    {
        $cases = $this->catalog->load($filter);
        $results = [];
        $ok = true;

        foreach ($cases as $case) {
            $pass = $this->scorer->score($case, $this->responses->passing($case));
            $fail = $this->scorer->score($case, $this->responses->failing($case));

            $notes = [];
            if (!$pass->passed() && $pass->status !== ScoreResult::SKIP) {
                $ok = false;
                $notes[] = 'self-check: expected passing synthetic response to pass';
                $notes = [...$notes, ...$pass->notes];
            }
            if ($fail->passed()) {
                // Failing synthetic should not pass — unless case has nothing to reject and
                // empty expected tokens (skip). If preferred tokens exist, failing response
                // omits them so should fail; if only must_not, failing includes must_not.
                $ok = false;
                $notes[] = 'self-check: expected failing synthetic response to fail';
            }

            $status = $notes === [] ? ScoreResult::PASS : ScoreResult::FAIL;
            if ($pass->status === ScoreResult::SKIP && $case->mustNot === []) {
                $status = ScoreResult::SKIP;
            }

            $results[] = new ScoreResult(
                $case->id,
                $status,
                $status === ScoreResult::PASS ? 1.0 : 0.0,
                $notes,
            );
        }

        return [
            'cases' => $cases,
            'results' => $results,
            'self_check_ok' => $ok,
            'by_category' => EvaluationCatalog::countByCategory($cases),
            'by_type' => EvaluationCatalog::countByType($cases),
            'fingerprint' => EvaluationCatalog::fingerprint($cases),
        ];
    }

    /**
     * @param list<EvaluationCase> $cases
     * @param list<ScoreResult> $results
     * @return array<string, mixed>
     */
    public function buildBaseline(
        array $cases,
        array $results,
        string $knowledgeVersion,
        string $model,
        string $modelVersion,
    ): array {
        return $this->baselines->build($cases, $results, $knowledgeVersion, $model, $modelVersion);
    }

    /**
     * @param array<string, mixed> $baseline
     * @param list<EvaluationCase> $cases
     * @param list<ScoreResult> $results
     * @return array{regressions: list<string>, changes: list<string>, ok: bool}
     */
    public function compareBaseline(array $baseline, array $cases, array $results): array
    {
        return $this->baselines->compare($cases, $results, $baseline);
    }

    public function baselineStore(): BaselineStore
    {
        return $this->baselines;
    }
}
