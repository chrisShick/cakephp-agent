<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

/**
 * Deterministic resolution result for a project.
 */
final class ResolutionResult
{
    /**
     * @param list<Extension> $enabled
     * @param list<ExtensionDecision> $decisions
     */
    public function __construct(
        public readonly array $enabled,
        public readonly array $decisions,
    ) {
    }

    /**
     * @return list<string>
     */
    public function enabledIds(): array
    {
        return array_map(static fn (Extension $e): string => $e->id(), $this->enabled);
    }

    public function decisionFor(string $id): ?ExtensionDecision
    {
        foreach ($this->decisions as $decision) {
            if ($decision->extensionId === $id) {
                return $decision;
            }
        }

        return null;
    }
}
