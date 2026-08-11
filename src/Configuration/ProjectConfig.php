<?php

declare(strict_types=1);

namespace CakePhpAgent\Configuration;

/**
 * Project configuration for cakephp-agent.
 *
 * Precedence (highest first): CLI flags > .cakephp-agent.json > composer.json extra > defaults
 */
final class ProjectConfig
{
    public const LOCK_FILENAME = '.cakephp-agent.lock.json';
    public const CONFIG_FILENAME = '.cakephp-agent.json';
    public const PROJECT_AI_DIR = '.ai';

    /**
     * @param list<string> $enableExtensions
     * @param list<string> $disableExtensions
     * @param list<string> $editors
     */
    public function __construct(
        public readonly string $projectRoot,
        public readonly array $editors = ['cursor'],
        public readonly bool $autoInstall = false,
        public readonly array $enableExtensions = [],
        public readonly array $disableExtensions = [],
        public readonly bool $force = false,
        public readonly bool $symlink = false,
        public readonly bool $prune = false,
        public readonly bool $dryRun = false,
        public readonly bool $verbose = false,
    ) {
    }

    /**
     * @param list<string>|null $editors
     * @param list<string>|null $enableExtensions
     * @param list<string>|null $disableExtensions
     */
    public function withCliOverrides(
        ?array $editors = null,
        ?bool $force = null,
        ?bool $symlink = null,
        ?bool $prune = null,
        ?bool $dryRun = null,
        ?bool $verbose = null,
        ?array $enableExtensions = null,
        ?array $disableExtensions = null,
    ): self {
        return new self(
            projectRoot: $this->projectRoot,
            editors: $editors ?? $this->editors,
            autoInstall: $this->autoInstall,
            enableExtensions: $enableExtensions ?? $this->enableExtensions,
            disableExtensions: $disableExtensions ?? $this->disableExtensions,
            force: $force ?? $this->force,
            symlink: $symlink ?? $this->symlink,
            prune: $prune ?? $this->prune,
            dryRun: $dryRun ?? $this->dryRun,
            verbose: $verbose ?? $this->verbose,
        );
    }
}
