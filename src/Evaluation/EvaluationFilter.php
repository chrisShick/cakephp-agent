<?php

declare(strict_types=1);

namespace CakePhpAgent\Evaluation;

/**
 * Filters for selecting evaluation fixtures.
 */
final class EvaluationFilter
{
    /**
     * @param list<string> $categories
     * @param list<string> $types
     * @param list<string> $ids
     * @param list<string> $extensions When non-empty, keep cases that require any listed extension,
     *                                 or have no extension requirements (core corpus).
     */
    public function __construct(
        public readonly array $categories = [],
        public readonly array $types = [],
        public readonly array $ids = [],
        public readonly array $extensions = [],
    ) {
    }

    public function matches(EvaluationCase $case): bool
    {
        if ($this->ids !== [] && !in_array($case->id, $this->ids, true)) {
            return false;
        }

        if ($this->categories !== [] && !in_array($case->category, $this->categories, true)) {
            return false;
        }

        if ($this->types !== [] && !in_array($case->type, $this->types, true)) {
            return false;
        }

        if ($this->extensions !== []) {
            if ($case->requiredExtensions === []) {
                return true;
            }

            foreach ($case->requiredExtensions as $required) {
                if (in_array($required, $this->extensions, true)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }
}
