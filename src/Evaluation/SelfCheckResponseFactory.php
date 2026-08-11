<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * Builds deterministic synthetic responses for scorer self-checks (no live model).
 */
final class SelfCheckResponseFactory
{
    /**
     * Response that should pass the heuristic scorer for this case.
     */
    public function passing(EvaluationCase $case): string
    {
        $parts = [
            'CakePHP-native answer for this evaluation case.',
        ];

        foreach ($case->preferred as $token) {
            $parts[] = 'Prefer: ' . $token;
        }
        foreach ($case->concepts as $token) {
            $parts[] = 'Concept: ' . $token;
        }

        if ($case->preferred === [] && $case->concepts === [] && $case->mustNot !== []) {
            $parts[] = 'Avoid the prohibited approaches; use CakePHP extension points instead.';
        }

        return implode("\n", $parts);
    }

    /**
     * Response that should fail (includes a must_not token when available).
     */
    public function failing(EvaluationCase $case): string
    {
        if ($case->mustNot !== []) {
            return 'I would use ' . $case->mustNot[0] . ' as the primary approach.';
        }

        return 'Unrelated answer with none of the expected CakePHP tokens.';
    }
}
