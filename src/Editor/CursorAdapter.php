<?php

declare(strict_types=1);

namespace CakePhpAgent\Editor;

final class CursorAdapter implements EditorAdapterInterface
{
    public function id(): string
    {
        return 'cursor';
    }

    public function displayName(): string
    {
        return 'Cursor';
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
        return $projectRoot . '/.cursor/rules/cakephp-agent';
    }

    public function skillsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.cursor/skills/cakephp-agent';
    }

    public function agentsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.cursor/agents/cakephp-agent';
    }
}
