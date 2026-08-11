<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Discovery;

use CakePhpAgent\Discovery\ComposerMetadataReader;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class ComposerMetadataReaderTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = TestTemp::dir('composer');
    }

    protected function tearDown(): void
    {
        TestTemp::removeTree($this->tempDir);
    }

    public function testPrefersLockVersions(): void
    {
        file_put_contents($this->tempDir . '/composer.json', json_encode([
            'require' => [
                'cakephp/cakephp' => '^5.0',
                'friendsofcake/crud' => '^7.0',
            ],
        ], JSON_THROW_ON_ERROR));

        file_put_contents($this->tempDir . '/composer.lock', json_encode([
            'packages' => [
                ['name' => 'cakephp/cakephp', 'version' => '5.4.2'],
                ['name' => 'friendsofcake/crud', 'version' => '7.1.0'],
            ],
            'packages-dev' => [],
        ], JSON_THROW_ON_ERROR));

        $packages = (new ComposerMetadataReader())->installedPackages($this->tempDir);

        self::assertSame('5.4.2', $packages['cakephp/cakephp']);
        self::assertSame('7.1.0', $packages['friendsofcake/crud']);
    }

    public function testFallsBackToComposerJsonConstraints(): void
    {
        file_put_contents($this->tempDir . '/composer.json', json_encode([
            'require' => [
                'php' => '^8.2',
                'cakephp/cakephp' => '^5.4',
            ],
        ], JSON_THROW_ON_ERROR));

        $packages = (new ComposerMetadataReader())->installedPackages($this->tempDir);

        self::assertSame('^5.4', $packages['cakephp/cakephp']);
        self::assertArrayNotHasKey('php', $packages);
    }
}
