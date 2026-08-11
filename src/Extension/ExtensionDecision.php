<?php

declare(strict_types=1);

namespace CakePhpAgent\Extension;

/**
 * Why an extension is or is not active for a project.
 */
final class ExtensionDecision
{
    public const ENABLED = 'enabled';
    public const DISABLED = 'disabled';
    public const INCOMPATIBLE = 'incompatible';
    public const UNDETECTED = 'undetected';

    public function __construct(
        public readonly string $extensionId,
        public readonly string $status,
        public readonly string $reason,
        public readonly ?Extension $extension = null,
    ) {
    }
}
