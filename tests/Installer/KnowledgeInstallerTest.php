<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Installer;

use CakePhpAgent\Configuration\ProjectConfig;
use CakePhpAgent\Editor\EditorRegistry;
use CakePhpAgent\Installer\InstallAction;
use CakePhpAgent\Installer\KnowledgeInstaller;
use CakePhpAgent\PackagePaths;
use CakePhpAgent\Test\Editor\FakeEditorAdapter;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class KnowledgeInstallerTest extends TestCase
{
    private string $projectRoot;
    private string $packageRoot;
    private KnowledgeInstaller $installer;

    protected function setUp(): void
    {
        $this->projectRoot = TestTemp::dir('project');
        $this->packageRoot = TestTemp::dir('pkg');

        mkdir($this->packageRoot . '/rules/engineering', 0775, true);
        mkdir($this->packageRoot . '/rules/php', 0775, true);
        mkdir($this->packageRoot . '/rules/cakephp', 0775, true);
        mkdir($this->packageRoot . '/skills/cakephp', 0775, true);
        mkdir($this->packageRoot . '/agents', 0775, true);

        file_put_contents($this->projectRoot . '/composer.json', '{"name":"fixture/app"}');
        file_put_contents($this->packageRoot . '/composer.json', '{"name":"chrisshick/cakephp-agent","version":"0.1.0"}');
        file_put_contents($this->packageRoot . '/rules/engineering/clean-code.mdc', "# clean\n");
        file_put_contents($this->packageRoot . '/rules/php/php.mdc', "# php\n");
        file_put_contents($this->packageRoot . '/rules/cakephp/conventions.mdc', "# cake\n");

        $this->installer = new KnowledgeInstaller(
            editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
            packageRoot: $this->packageRoot,
        );
    }

    protected function tearDown(): void
    {
        TestTemp::removeTree($this->projectRoot);
        TestTemp::removeTree($this->packageRoot);
    }

    public function testInstallCreatesManagedRulesForCursor(): void
    {
        $config = new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']);
        $result = $this->installer->install($config);

        self::assertFileExists($this->projectRoot . '/.editor/cursor/rules/cakephp-agent/engineering/clean-code.mdc');
        self::assertFileExists($this->projectRoot . '/.editor/cursor/rules/cakephp-agent/cakephp/conventions.mdc');
        self::assertFileExists($this->projectRoot . '/.cakephp-agent.lock.json');

        $creates = array_filter(
            $result['actions'],
            static fn ($a) => $a->action === InstallAction::CREATE
        );
        self::assertNotEmpty($creates);
    }

    public function testDryRunDoesNotWriteFiles(): void
    {
        $config = new ProjectConfig(
            projectRoot: $this->projectRoot,
            editors: ['cursor'],
            dryRun: true,
        );

        $this->installer->install($config);

        self::assertDirectoryDoesNotExist($this->projectRoot . '/.editor');
        self::assertFileDoesNotExist($this->projectRoot . '/.cakephp-agent.lock.json');
    }

    public function testPreservesUnmanagedExistingFileWithoutForce(): void
    {
        $target = $this->projectRoot . '/.editor/cursor/rules/cakephp-agent/engineering/clean-code.mdc';
        mkdir(dirname($target), 0775, true);
        file_put_contents($target, "# local override\n");

        $config = new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']);
        $result = $this->installer->install($config);

        self::assertSame("# local override\n", file_get_contents($target));

        $preserved = array_values(array_filter(
            $result['actions'],
            static fn ($a) => $a->relativePath === '.editor/cursor/rules/cakephp-agent/engineering/clean-code.mdc'
        ));
        self::assertSame(InstallAction::PRESERVE, $preserved[0]->action);
    }

    public function testForceOverwritesUnmanagedFile(): void
    {
        $target = $this->projectRoot . '/.editor/cursor/rules/cakephp-agent/engineering/clean-code.mdc';
        mkdir(dirname($target), 0775, true);
        file_put_contents($target, "# local override\n");

        $config = new ProjectConfig(
            projectRoot: $this->projectRoot,
            editors: ['cursor'],
            force: true,
        );
        $this->installer->install($config);

        self::assertSame("# clean\n", file_get_contents($target));
    }

    public function testPruneRemovesOnlyPreviouslyManagedFiles(): void
    {
        $this->installer->install(new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']));

        $orphanRel = '.editor/cursor/rules/cakephp-agent/engineering/orphan.mdc';
        $orphan = $this->projectRoot . '/' . $orphanRel;
        file_put_contents($orphan, "# orphan\n");

        $lockPath = $this->projectRoot . '/.cakephp-agent.lock.json';
        $lock = json_decode((string) file_get_contents($lockPath), true, 512, JSON_THROW_ON_ERROR);
        $lock['files'][$orphanRel] = [
            'hash' => hash_file('sha256', $orphan),
            'editor' => 'cursor',
            'kind' => 'rule',
        ];
        file_put_contents($lockPath, json_encode($lock, JSON_THROW_ON_ERROR));

        $userFile = $this->projectRoot . '/.editor/cursor/rules/user-rule.mdc';
        file_put_contents($userFile, "# user\n");

        $this->installer->install(new ProjectConfig(
            projectRoot: $this->projectRoot,
            editors: ['cursor'],
            prune: true,
        ));

        self::assertFileDoesNotExist($orphan);
        self::assertFileExists($userFile);
    }

    public function testSecondInstallSkipsUnchangedManagedFiles(): void
    {
        $config = new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']);
        $this->installer->install($config);
        $second = $this->installer->install($config);

        $skips = array_filter(
            $second['actions'],
            static fn ($a) => $a->action === InstallAction::SKIP
        );
        self::assertNotEmpty($skips);
    }

    public function testInstallsNestedCakephpSkillFolders(): void
    {
        $skillDir = $this->packageRoot . '/skills/cakephp/inspect-before-coding';
        mkdir($skillDir, 0775, true);
        file_put_contents($skillDir . '/SKILL.md', "# inspect\n");

        $config = new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']);
        $this->installer->install($config);

        self::assertFileExists(
            $this->projectRoot . '/.editor/cursor/skills/cakephp-agent/cakephp/inspect-before-coding/SKILL.md'
        );
    }

    public function testRealPackageDryRunPlansCoreSkills(): void
    {
        $project = TestTemp::dir('real-skills-project');
        file_put_contents($project . '/composer.json', '{"name":"fixture/app","require":{"cakephp/cakephp":"^5.0"}}');

        try {
            $installer = new KnowledgeInstaller(
                editors: new EditorRegistry([new FakeEditorAdapter('cursor')]),
                packageRoot: PackagePaths::root(),
            );

            $result = $installer->install(new ProjectConfig(
                projectRoot: $project,
                editors: ['cursor'],
                dryRun: true,
            ));

            $skillPaths = [];
            $agentPaths = [];
            foreach ($result['actions'] as $action) {
                if (str_contains($action->relativePath, 'skills/cakephp-agent/cakephp/')
                    && str_ends_with($action->relativePath, 'SKILL.md')
                ) {
                    $skillPaths[] = $action->relativePath;
                }
                if (str_contains($action->relativePath, 'agents/cakephp-agent/')
                    && str_ends_with($action->relativePath, '.md')
                ) {
                    $agentPaths[] = $action->relativePath;
                }
            }

            self::assertNotEmpty($skillPaths);
            self::assertTrue(
                (bool) array_filter(
                    $skillPaths,
                    static fn (string $p): bool => str_contains($p, 'inspect-before-coding/SKILL.md')
                ),
                'Expected inspect-before-coding skill in dry-run plan'
            );
            self::assertTrue(
                (bool) array_filter(
                    $skillPaths,
                    static fn (string $p): bool => str_contains($p, 'choose-cakephp-abstraction/SKILL.md')
                ),
                'Expected choose-cakephp-abstraction skill in dry-run plan'
            );
            self::assertTrue(
                (bool) array_filter(
                    $skillPaths,
                    static fn (string $p): bool => str_contains($p, 'detect-architectural-smells/SKILL.md')
                ),
                'Expected detect-architectural-smells skill in dry-run plan'
            );
            self::assertTrue(
                (bool) array_filter(
                    $skillPaths,
                    static fn (string $p): bool => str_contains($p, 'review-abstraction-choice/SKILL.md')
                ),
                'Expected review-abstraction-choice skill in dry-run plan'
            );
            self::assertNotEmpty($agentPaths);
            self::assertTrue(
                (bool) array_filter(
                    $agentPaths,
                    static fn (string $p): bool => str_ends_with($p, 'cakephp-code-reviewer.md')
                ),
                'Expected cakephp-code-reviewer agent in dry-run plan'
            );
            self::assertCount(4, array_filter(
                $agentPaths,
                static fn (string $p): bool => (bool) preg_match(
                    '#agents/cakephp-agent/cakephp-(code|orm|security|architecture)-reviewer\.md$#',
                    $p
                )
            ));
        } finally {
            TestTemp::removeTree($project);
        }
    }

    public function testInstallsAgentFilesFromPackage(): void
    {
        file_put_contents($this->packageRoot . '/agents/cakephp-code-reviewer.md', "# reviewer\n");

        $config = new ProjectConfig(projectRoot: $this->projectRoot, editors: ['cursor']);
        $this->installer->install($config);

        self::assertFileExists(
            $this->projectRoot . '/.editor/cursor/agents/cakephp-agent/cakephp-code-reviewer.md'
        );
    }

    public function testPackagePathsPointAtRealRepo(): void
    {
        self::assertDirectoryExists(PackagePaths::rules());
        self::assertDirectoryExists(PackagePaths::skills());
        self::assertDirectoryExists(PackagePaths::agents());
    }
}
