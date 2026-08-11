<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Validation;

use CakePhpAgent\PackagePaths;
use CakePhpAgent\Validation\ContentValidator;
use PHPUnit\Framework\TestCase;

final class ContentValidatorTest extends TestCase
{
    public function testPackageContentPassesValidation(): void
    {
        $errors = (new ContentValidator(packageRoot: PackagePaths::root()))->validate();

        self::assertSame([], $errors, implode("\n", $errors));
    }
}
