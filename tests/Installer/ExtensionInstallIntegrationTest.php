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
        } finally {
            TestTemp::removeTree($only);
        }
    }
}
