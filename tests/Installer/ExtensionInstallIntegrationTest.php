<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Installer;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Extension\ExtensionRegistry;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Installer\InstallAction;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use CakePhpAgent\Test\Editor\FakeEditorAdapter;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class ExtensionInstallIntegrationTest extends TestCase
{
    private string $projectRoot;

    protected function setUp(): void
    {
        $this->projectRoot = TestTemp::dir('ext-install');
        // Copy fixture composer metadata into an isolated writable project.
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-fake';
        copy($fixture . '/composer.json', $this->projectRoot . '/composer.json');
        copy($fixture . '/composer.lock', $this->projectRoot . '/composer.lock');
    }

    protected function tearDown(): void
    {
        TestTemp::removeTree($this->projectRoot);
    }

    public function testInstallIncludesDetectedExtensionContent(): void
    {
        $resolver = new ExtensionResolver(
            new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
        );
        $installer = new KnowledgeInstaller(
            editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
            extensionResolver: $resolver,
            packageRoot: PackagePaths::root(),
        );

        $result = $installer->install(new ProjectConfig(
            projectRoot: $this->projectRoot,
            editors: ['cursor'],
        ));

        self::assertContains('test-fake-plugin', $result['resolution']->enabledIds());
        self::assertFileExists(
            $this->projectRoot . '/.editor/cursor/rules/cakephp-agent/extensions/test-fake-plugin/fake-plugin.mdc'
        );
        self::assertFileExists(
            $this->projectRoot . '/.editor/cursor/skills/cakephp-agent/extensions/test-fake-plugin/review-fake-plugin/SKILL.md'
        );
        self::assertSame(['test-fake-plugin'], $result['state']->extensions);
    }

    public function testDisablePreventsExtensionInstall(): void
    {
        $resolver = new ExtensionResolver(
            new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
        );
        $installer = new KnowledgeInstaller(
            editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
            extensionResolver: $resolver,
            packageRoot: PackagePaths::root(),
        );

        $result = $installer->install(new ProjectConfig(
            projectRoot: $this->projectRoot,
            editors: ['cursor'],
            disableExtensions: ['test-fake-plugin'],
        ));

        self::assertNotContains('test-fake-plugin', $result['resolution']->enabledIds());
        self::assertFileDoesNotExist(
            $this->projectRoot . '/.editor/cursor/rules/cakephp-agent/extensions/test-fake-plugin/fake-plugin.mdc'
        );

        $extensionActions = array_filter(
            $result['actions'],
            static fn ($a) => str_contains($a->relativePath, 'test-fake-plugin')
        );
        self::assertSame([], array_values($extensionActions));
    }

    public function testCoreRulesStillInstallWithoutExtensions(): void
    {
        $resolver = new ExtensionResolver(
            new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
        );
        $installer = new KnowledgeInstaller(
            editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
            extensionResolver: $resolver,
            packageRoot: PackagePaths::root(),
        );

        $only = TestTemp::dir('core-only');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-only';
        copy($fixture . '/composer.json', $only . '/composer.json');
        copy($fixture . '/composer.lock', $only . '/composer.lock');

        try {
            $result = $installer->install(new ProjectConfig(
                projectRoot: $only,
                editors: ['cursor'],
            ));

            self::assertFileExists($only . '/.editor/cursor/rules/cakephp-agent/cakephp/conventions.mdc');
            self::assertSame([], $result['resolution']->enabledIds());
            $creates = array_filter(
                $result['actions'],
                static fn ($a) => $a->action === InstallAction::CREATE
            );
            self::assertNotEmpty($creates);
            $crudActions = array_filter(
                $result['actions'],
                static fn ($a) => str_contains($a->relativePath, 'friendsofcake-crud')
            );
            self::assertSame([], array_values($crudActions));
        } finally {
            TestTemp::removeTree($only);
        }
    }

    public function testCrudExtensionInstallsWhenDetected(): void
    {
        $project = TestTemp::dir('crud-install');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-crud';
        copy($fixture . '/composer.json', $project . '/composer.json');
        copy($fixture . '/composer.lock', $project . '/composer.lock');

        try {
            $resolver = new ExtensionResolver(
                new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
            );
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
            ));

            self::assertContains('friendsofcake-crud', $result['resolution']->enabledIds());
            self::assertFileExists(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/friendsofcake-crud/crud.mdc'
            );
            self::assertFileExists(
                $project . '/.editor/cursor/skills/cakephp-agent/extensions/friendsofcake-crud/create-crud-listener/SKILL.md'
            );
        } finally {
            TestTemp::removeTree($project);
        }
    }

    public function testCrudDisabledDoesNotInstallPack(): void
    {
        $project = TestTemp::dir('crud-disabled');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-crud';
        copy($fixture . '/composer.json', $project . '/composer.json');
        copy($fixture . '/composer.lock', $project . '/composer.lock');

        try {
            $resolver = new ExtensionResolver(
                new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
            );
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
                disableExtensions: ['friendsofcake-crud'],
            ));

            self::assertNotContains('friendsofcake-crud', $result['resolution']->enabledIds());
            self::assertFileDoesNotExist(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/friendsofcake-crud/crud.mdc'
            );
        } finally {
            TestTemp::removeTree($project);
        }
    }

    public function testCrudIncompatibleDoesNotInstallPack(): void
    {
        $project = TestTemp::dir('crud-incompatible');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-crud-incompatible';
        copy($fixture . '/composer.json', $project . '/composer.json');
        copy($fixture . '/composer.lock', $project . '/composer.lock');

        try {
            $resolver = new ExtensionResolver(
                new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
            );
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
            ));

            self::assertNotContains('friendsofcake-crud', $result['resolution']->enabledIds());
            self::assertSame(
                \CakePhpAgent\Extension\ExtensionDecision::INCOMPATIBLE,
                $result['resolution']->decisionFor('friendsofcake-crud')?->status
            );
            self::assertFileDoesNotExist(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/friendsofcake-crud/crud.mdc'
            );
        } finally {
            TestTemp::removeTree($project);
        }
    }

    public function testAuthenticationOnlyInstallsAuthnNotAuthz(): void
    {
        $project = TestTemp::dir('authn-only');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-authentication';
        copy($fixture . '/composer.json', $project . '/composer.json');
        copy($fixture . '/composer.lock', $project . '/composer.lock');

        try {
            $resolver = new ExtensionResolver(
                new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
            );
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
            ));

            self::assertContains('cakephp-authentication', $result['resolution']->enabledIds());
            self::assertNotContains('cakephp-authorization', $result['resolution']->enabledIds());
            self::assertFileExists(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/cakephp-authentication/authentication.mdc'
            );
            self::assertFileDoesNotExist(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/cakephp-authorization/authorization.mdc'
            );
            self::assertFileDoesNotExist(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/cakephp-authentication-authorization/identity-feeds-authorization.mdc'
            );
        } finally {
            TestTemp::removeTree($project);
        }
    }

    public function testAuthBothInstallsIntegrationPack(): void
    {
        $project = TestTemp::dir('auth-both');
        $fixture = PackagePaths::root() . '/tests/fixtures/projects/cakephp-auth-both';
        copy($fixture . '/composer.json', $project . '/composer.json');
        copy($fixture . '/composer.lock', $project . '/composer.lock');

        try {
            $resolver = new ExtensionResolver(
                new ExtensionRegistry(new ManifestLoader(), PackagePaths::root())
            );
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
            ));

            self::assertContains('cakephp-authentication-authorization', $result['resolution']->enabledIds());
            self::assertFileExists(
                $project . '/.editor/cursor/rules/cakephp-agent/extensions/cakephp-authentication-authorization/identity-feeds-authorization.mdc'
            );
        } finally {
            TestTemp::removeTree($project);
        }
    }
}
