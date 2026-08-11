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
            new ExtensionRegistry(new ManifestLoader(), PackagePaths::root(), true)
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

    public function testCrudDetectedWhenPresent(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-crud',
        ));

        self::assertContains('friendsofcake-crud', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::ENABLED,
            $result->decisionFor('friendsofcake-crud')?->status
        );
    }

    public function testCakephpOnlyDoesNotEnableCrud(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-only',
        ));

        self::assertNotContains('friendsofcake-crud', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::UNDETECTED,
            $result->decisionFor('friendsofcake-crud')?->status
        );
    }

    public function testCrudIncompatibleMajorReported(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-crud-incompatible',
        ));

        self::assertNotContains('friendsofcake-crud', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::INCOMPATIBLE,
            $result->decisionFor('friendsofcake-crud')?->status
        );
    }

    public function testCrudExplicitDisableOverridesDetection(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-crud',
            disableExtensions: ['friendsofcake-crud'],
        ));

        self::assertNotContains('friendsofcake-crud', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::DISABLED,
            $result->decisionFor('friendsofcake-crud')?->status
        );
    }

    public function testAuthenticationOnlyDoesNotEnableAuthorization(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-authentication',
        ));

        self::assertContains('cakephp-authentication', $result->enabledIds());
        self::assertNotContains('cakephp-authorization', $result->enabledIds());
        self::assertNotContains('cakephp-authentication-authorization', $result->enabledIds());
    }

    public function testAuthorizationOnlyDoesNotEnableAuthentication(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-authorization',
        ));

        self::assertContains('cakephp-authorization', $result->enabledIds());
        self::assertNotContains('cakephp-authentication', $result->enabledIds());
        self::assertNotContains('cakephp-authentication-authorization', $result->enabledIds());
    }

    public function testBothAuthPackagesActivateIntegration(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-auth-both',
        ));

        $ids = $result->enabledIds();
        self::assertContains('cakephp-authentication', $ids);
        self::assertContains('cakephp-authorization', $ids);
        self::assertContains('cakephp-authentication-authorization', $ids);
    }

    public function testAuthenticationIncompatibleMajorReported(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-authentication-incompatible',
        ));

        self::assertNotContains('cakephp-authentication', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::INCOMPATIBLE,
            $result->decisionFor('cakephp-authentication')?->status
        );
    }

    public function testSearchOnlyDoesNotEnableCrud(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-search',
        ));

        self::assertContains('friendsofcake-search', $result->enabledIds());
        self::assertNotContains('friendsofcake-crud', $result->enabledIds());
        self::assertNotContains('friendsofcake-crud-search', $result->enabledIds());
    }

    public function testCrudOnlyDoesNotEnableSearch(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-crud',
        ));

        self::assertContains('friendsofcake-crud', $result->enabledIds());
        self::assertNotContains('friendsofcake-search', $result->enabledIds());
        self::assertNotContains('friendsofcake-crud-search', $result->enabledIds());
    }

    public function testCrudAndSearchActivateIntegration(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-crud-search',
        ));

        $ids = $result->enabledIds();
        self::assertContains('friendsofcake-crud', $ids);
        self::assertContains('friendsofcake-search', $ids);
        self::assertContains('friendsofcake-crud-search', $ids);
    }

    public function testSearchIncompatibleMajorReported(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-search-incompatible',
        ));

        self::assertNotContains('friendsofcake-search', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::INCOMPATIBLE,
            $result->decisionFor('friendsofcake-search')?->status
        );
    }

    public function testMigrationsDetectedWhenPresent(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-migrations',
        ));

        self::assertContains('cakephp-migrations', $result->enabledIds());
        self::assertNotContains('cakephp-bake', $result->enabledIds());
    }

    public function testMigrationsIncompatibleMajorReported(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-migrations-incompatible',
        ));

        self::assertNotContains('cakephp-migrations', $result->enabledIds());
        self::assertSame(
            ExtensionDecision::INCOMPATIBLE,
            $result->decisionFor('cakephp-migrations')?->status
        );
    }

    public function testBakeDetectedWhenPresent(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-bake',
        ));

        self::assertContains('cakephp-bake', $result->enabledIds());
        self::assertNotContains('cakephp-migrations', $result->enabledIds());
    }

    public function testCakephpOnlyDoesNotEnableMigrationsOrBake(): void
    {
        $result = $this->resolver->resolve(new ProjectConfig(
            projectRoot: $this->fixtures . '/cakephp-only',
        ));

        self::assertNotContains('cakephp-migrations', $result->enabledIds());
        self::assertNotContains('cakephp-bake', $result->enabledIds());
    }
}
