<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Discovery;

use CakePhpAgent\Discovery\VersionMatcher;
use PHPUnit\Framework\TestCase;

final class VersionMatcherTest extends TestCase
{
    private VersionMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new VersionMatcher();
    }

    public function testExactVersionSatisfiesConstraint(): void
    {
        self::assertTrue($this->matcher->satisfies('1.2.0', '^1.0'));
        self::assertFalse($this->matcher->satisfies('0.9.0', '^1.0'));
        self::assertTrue($this->matcher->satisfies('5.4.2', '^5.0'));
    }

    public function testConstraintAgainstConstraint(): void
    {
        self::assertTrue($this->matcher->satisfies('^5.4', '^5.0'));
        self::assertFalse($this->matcher->satisfies('^4.0', '^5.0'));
    }

    public function testStarMatchesAnything(): void
    {
        self::assertTrue($this->matcher->satisfies('9.9.9', '*'));
    }
}
