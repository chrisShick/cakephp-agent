<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Configuration;

use CakePhpAgent\Configuration\ProjectConfigLoader;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class ProjectConfigLoaderTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = TestTemp::dir('config');
        file_put_contents($this->tempDir . '/composer.json', json_encode([
            'name' => 'fixture/app',
            'extra' => [
                'cakephp-agent' => [
                    'editor' => 'claude',
                    'extensions' => [
                        'enable' => ['architecture-api-only'],
                        'disable' => ['cakephp-bake'],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR));
    }

    protected function tearDown(): void
    {
        TestTemp::removeTree($this->tempDir);
    }

    public function testLoadsFromComposerExtra(): void
    {
        $config = (new ProjectConfigLoader())->load($this->tempDir);

        self::assertSame(['claude'], $config->editors);
        self::assertSame(['architecture-api-only'], $config->enableExtensions);
        self::assertSame(['cakephp-bake'], $config->disableExtensions);
    }

    public function testConfigFileOverridesComposerExtra(): void
    {
        file_put_contents($this->tempDir . '/.cakephp-agent.json', json_encode([
            'editor' => 'cursor',
            'extensions' => [
                'disable' => ['friendsofcake-crud'],
            ],
        ], JSON_THROW_ON_ERROR));

        $config = (new ProjectConfigLoader())->load($this->tempDir);

        self::assertSame(['cursor'], $config->editors);
        self::assertSame(['friendsofcake-crud'], $config->disableExtensions);
        self::assertSame(['architecture-api-only'], $config->enableExtensions);
    }
}
