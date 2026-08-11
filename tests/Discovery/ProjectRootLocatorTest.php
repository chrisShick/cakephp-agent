<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Discovery;

use CakePhpAgent\Discovery\ProjectRootLocator;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ProjectRootLocatorTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = TestTemp::dir('root');
        mkdir($this->tempDir . '/app/src', 0775, true);
        file_put_contents($this->tempDir . '/app/composer.json', '{"name":"fixture/app"}');
    }

    protected function tearDown(): void
    {
        TestTemp::removeTree($this->tempDir);
    }

    public function testLocatesRootFromNestedDirectory(): void
    {
        $locator = new ProjectRootLocator();
        $root = $locator->locate($this->tempDir . '/app/src');

        self::assertSame(realpath($this->tempDir . '/app'), $root);
    }

    public function testThrowsWhenNoComposerJson(): void
    {
        $empty = $this->tempDir . '/empty';
        mkdir($empty);

        $this->expectException(RuntimeException::class);
        (new ProjectRootLocator())->locate($empty, $this->tempDir);
    }
}
