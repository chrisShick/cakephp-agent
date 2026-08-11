<?php

declare(strict_types=1);

namespace CakePhpAgent\Editor;

/**
 * Codex adapter — paths follow current Codex AGENTS.md / .codex conventions.
 */
final class CodexAdapter implements EditorAdapterInterface
{
    public function id(): string
    {
        return 'codex';
    }

    public function displayName(): string
    {
        return 'Codex';
    }

    public function supportsRules(): bool
    {
        return true;
    }

    public function supportsSkills(): bool
    {
        return true;
    }

    public function supportsAgents(): bool
    {
        return false;
    }

    public function rulesDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.codex/rules/cakephp-agent';
    }

    public function skillsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.codex/skills/cakephp-agent';
    }

    public function agentsDirectory(string $projectRoot): ?string
    {
        return null;
    }
}
