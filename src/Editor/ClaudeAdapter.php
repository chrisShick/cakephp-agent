<?php

declare(strict_types=1);

namespace CakePhpAgent\Editor;

/**
 * Claude Code adapter.
 *
 * Skills land under .claude/skills; rules under .claude/rules.
 * Project-owned overlays remain in .ai/ (highest precedence by convention).
 */
final class ClaudeAdapter implements EditorAdapterInterface
{
    public function id(): string
    {
        return 'claude';
    }

    public function displayName(): string
    {
        return 'Claude Code';
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
        return true;
    }

    public function rulesDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.claude/rules/cakephp-agent';
    }

    public function skillsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.claude/skills/cakephp-agent';
    }

    public function agentsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.claude/agents/cakephp-agent';
    }
}
