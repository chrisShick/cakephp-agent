<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Installer;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Extension\ExtensionRegistry;
use CakePhpAgent\Extension\ExtensionResolver;
use CakePhpAgent\Filesystem\Filesystem;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\Manifest\ManifestLoader;
use CakePhpAgent\PackagePaths;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class UninstallTest extends TestCase
{
    public function testUninstallRemovesLockTrackedFiles(): void
    {
        $project = TestTemp::dir('uninstall-project');
        $fs = new Filesystem();

        try {
            $registry = new ExtensionRegistry(new ManifestLoader(), PackagePaths::root(), true);
            $resolver = new ExtensionResolver(registry: $registry);

            $fs->write($project . '/composer.json', json_encode([
                'name' => 'app/test',
                'require' => [
                    'php' => '^8.2',
                    'cakephp/cakephp' => '^5.4',
                    'cakephp-agent/fake-plugin' => '^1.0',
                ],
            ], JSON_THROW_ON_ERROR));
            // Minimal lock so ComposerMetadataReader can resolve packages if needed
            $fs->write($project . '/composer.lock', json_encode([
                'packages' => [
                    ['name' => 'cakephp/cakephp', 'version' => '5.4.0'],
                    ['name' => 'cakephp-agent/fake-plugin', 'version' => '1.0.0'],
                ],
                'packages-dev' => [],
            ], JSON_THROW_ON_ERROR));

            $installer = new KnowledgeInstaller(
                filesystem: $fs,
                editors: new EditorRegistry([new \CakePhpAgent\Test\Editor\FakeEditorAdapter('cursor')]),
                extensionResolver: $resolver,
                packageRoot: PackagePaths::root(),
            );

            $config = new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
                enableExtensions: ['test-fake-plugin'],
            );
            $install = $installer->install($config);
            self::assertNotEmpty($install['actions']);
            self::assertFileExists($project . '/.cakephp-agent.lock.json');

            $managed = array_keys($install['state']->files);
            self::assertNotEmpty($managed);
            foreach ($managed as $relative) {
                self::assertFileExists($project . '/' . $relative);
            }

            $result = $installer->uninstall(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
            ));
            self::assertTrue($result['removedLock']);
            self::assertFileDoesNotExist($project . '/.cakephp-agent.lock.json');
            foreach ($managed as $relative) {
                self::assertFileDoesNotExist($project . '/' . $relative);
            }
        } finally {
            TestTemp::removeTree($project);
        }
    }
}
