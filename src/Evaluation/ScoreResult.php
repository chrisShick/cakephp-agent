<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * Result of scoring one model (or fixture) response against an evaluation case.
 */
final class ScoreResult
{
    public const PASS = 'pass';
    public const FAIL = 'fail';
    public const SKIP = 'skip';

    /**
     * @param list<string> $notes
     */
    public function __construct(
        public readonly string $evaluationId,
        public readonly string $status,
        public readonly float $score,
        public readonly array $notes = [],
    ) {
    }

    public function passed(): bool
    {
        return $this->status === self::PASS;
    }
}
