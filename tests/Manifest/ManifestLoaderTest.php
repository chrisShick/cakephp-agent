<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Manifest;

use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use PHPUnit\Framework\TestCase;

final class ManifestLoaderTest extends TestCase
{
    public function testConsumerLoadExcludesTestFixtures(): void
    {
        $extensions = (new ManifestLoader())->loadAll(PackagePaths::root());
        $ids = array_map(static fn ($e) => $e->id(), $extensions);

        self::assertNotContains('test-fake-plugin', $ids);
        self::assertNotContains('test-fake-addon', $ids);
        self::assertContains('friendsofcake-crud', $ids);
        self::assertContains('cakephp-authentication', $ids);
        self::assertContains('cakephp-authorization', $ids);
        self::assertContains('cakephp-authentication-authorization', $ids);
        self::assertContains('friendsofcake-search', $ids);
        self::assertContains('friendsofcake-crud-search', $ids);
    }

    public function testTestFixturesLoadWhenRequested(): void
    {
        $extensions = (new ManifestLoader())->loadAll(PackagePaths::root(), true);
        $ids = array_map(static fn ($e) => $e->id(), $extensions);

        self::assertContains('test-fake-plugin', $ids);
        self::assertContains('test-fake-addon', $ids);
    }

    public function testFakePluginManifestFields(): void
    {
        $extension = (new ManifestLoader())->loadOne(
            PackagePaths::root() . '/tests/fixtures/extensions/test-fake-plugin'
        );

        self::assertSame('test-fake-plugin', $extension->id());
        self::assertSame('composer-package', $extension->manifest->type);
        self::assertTrue($extension->manifest->defaultEnabledWhenDetected);
        self::assertSame('cakephp-agent/fake-plugin', $extension->manifest->detectComposer[0]['package']);
    }

    public function testFriendsOfCakeCrudManifestDetectsPackageConstraint(): void
    {
        $extension = (new ManifestLoader())->loadOne(
            PackagePaths::root() . '/extensions/friendsofcake-crud'
        );

        self::assertSame('friendsofcake-crud', $extension->id());
        self::assertSame('friendsofcake/crud', $extension->manifest->detectComposer[0]['package']);
        self::assertSame('^7.0', $extension->manifest->detectComposer[0]['constraint']);
        self::assertDirectoryExists($extension->rulesDirectory());
        self::assertDirectoryExists($extension->skillsDirectory());
    }
}
