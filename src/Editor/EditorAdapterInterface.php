<?php

declare(strict_types=1);

namespace CakePhpAgent\Editor;

/**
 * Editor-specific target paths. Canonical knowledge stays editor-agnostic.
 */
interface EditorAdapterInterface
{
    public function id(): string;

    public function displayName(): string;

    public function supportsRules(): bool;

    public function supportsSkills(): bool;

    public function supportsAgents(): bool;

    /**
     * Absolute path where package-managed rules should be installed.
     */
    public function rulesDirectory(string $projectRoot): string;

    /**
     * Absolute path where package-managed skills should be installed.
     */
    public function skillsDirectory(string $projectRoot): string;

    /**
     * Absolute path where package-managed agents should be installed, if any.
     */
    public function agentsDirectory(string $projectRoot): ?string;
}
