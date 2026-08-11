<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * Heuristic scorer for offline plumbing — not a live model judge.
 *
 * Passes when preferred/concept tokens appear and must_not tokens do not.
 */
final class HeuristicScorer
{
    public function score(EvaluationCase $case, string $response): ScoreResult
    {
        $notes = [];
        $haystack = mb_strtolower($response);
        $hits = 0;
        $needed = 0;

        foreach ([...$case->preferred, ...$case->concepts] as $token) {
            $needed++;
            if ($this->containsToken($haystack, $token)) {
                $hits++;
            } else {
                $notes[] = sprintf('missing expected token: %s', $token);
            }
        }

        $prohibitedHits = 0;
        foreach ($case->mustNot as $token) {
            if ($this->containsToken($haystack, $token)) {
                $prohibitedHits++;
                $notes[] = sprintf('hit prohibited token: %s', $token);
            }
        }

        if ($needed === 0 && $case->mustNot === []) {
            return new ScoreResult($case->id, ScoreResult::SKIP, 0.0, ['no scoreable tokens']);
        }

        if ($needed === 0) {
            // Rejection-only fixture: pass when no prohibited tokens appear.
            $passed = $prohibitedHits === 0;
            return new ScoreResult(
                $case->id,
                $passed ? ScoreResult::PASS : ScoreResult::FAIL,
                $passed ? 1.0 : 0.0,
                $notes,
            );
        }

        $coverage = $hits / $needed;
        $passed = $coverage >= 1.0 && $prohibitedHits === 0;

        return new ScoreResult(
            $case->id,
            $passed ? ScoreResult::PASS : ScoreResult::FAIL,
            round($coverage, 4),
            $notes,
        );
    }

    private function containsToken(string $haystackLower, string $token): bool
    {
        $needle = mb_strtolower(trim($token));
        if ($needle === '') {
            return false;
        }

        return str_contains($haystackLower, $needle);
    }
}
