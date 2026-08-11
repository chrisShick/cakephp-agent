<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Extension;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Extension\ExtensionDecision;
use CakePhpAgent\Extension\ExtensionRegistry;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use PHPUnit\Framework\TestCase;

final class ExtensionResolverTest extends TestCase
{
    private ExtensionResolver $resolver;
    private string $fixtures;

    protected function setUp(): void
    {
        $this->fixtures = PackagePaths::root() . '/tests/fixtures/projects';
        $this->resolver = new ExtensionResolver(
            new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
        );
    }

    public function testCakephpOnlyDoesNotEnableFakePlugin(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-only',
        ));

        self::assertNotContains('test-fake-plugin', $result->enabledIds());
        $decision = $result->decisionFor('test-fake-plugin');
        self::assertNotNull($decision);
        self::assertSame(ExtensionDecision::UNDETECTED, $decision->status);
    }

    public function testFakePluginDetectedWhenPresent(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-fake',
        ));

        self::assertContains('test-fake-plugin', $result->enabledIds());
        self::assertNotContains('test-fake-addon', $result->enabledIds());
        $decision = $result->decisionFor('test-fake-plugin');
        self::assertSame(ExtensionDecision::ENABLED, $decision?->status);
        self::assertStringContainsString('Detected via Composer', (string) $decision?->reason);
    }

    public function testIncompatibleVersionReported(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-fake-incompatible',
        ));

        self::assertNotContains('test-fake-plugin', $result->enabledIds());
        $decision = $result->decisionFor('test-fake-plugin');
        self::assertSame(ExtensionDecision::INCOMPATIBLE, $decision?->status);
    }

    public function testExplicitDisableOverridesDetection(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-fake',
            disableExtensions: ['test-fake-plugin'],
        ));

        self::assertNotContains('test-fake-plugin', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::DISABLED,
            $result->decisionFor('test-fake-plugin')?->status
        );
    }

    public function testAddonPullsInDependency(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-fake-addon',
        ));

        $ids = $result->enabledIds();
        self::assertContains('test-fake-plugin', $ids);
        self::assertContains('test-fake-addon', $ids);
        // Dependency should be ordered before dependent.
        self::assertLessThan(
            array_search('test-fake-addon', $ids, true),
            array_search('test-fake-plugin', $ids, true)
        );
    }

    public function testExplicitEnableWithoutComposerPackage(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-only',
            enableExtensions: ['test-fake-plugin'],
        ));

        self::assertContains('test-fake-plugin', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::ENABLED,
            $result->decisionFor('test-fake-plugin')?->status
        );
    }
}
