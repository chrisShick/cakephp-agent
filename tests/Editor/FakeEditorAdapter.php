<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Editor;

use CakePhpAgent\Editor\EditorAdapterInterface;

/**
 * Sandbox-safe adapter that avoids reserved .cursor paths during tests.
 */
final class FakeEditorAdapter implements EditorAdapterInterface
{
    public function __construct(
        private readonly string $id = 'cursor',
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function displayName(): string
    {
        return 'Fake ' . $this->id;
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
        return $projectRoot . '/.editor/' . $this->id . '/rules/cakephp-agent';
    }

    public function skillsDirectory(string $projectRoot): string
    {
        return $projectRoot . '/.editor/' . $this->id . '/skills/cakephp-agent';
    }

    public function agentsDirectory(string $projectRoot): ?string
    {
        return $projectRoot . '/.editor/' . $this->id . '/agents/cakephp-agent';
    }
}
