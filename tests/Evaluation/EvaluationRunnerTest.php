<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Evaluation;

use CakePhpAgent\Evaluation\EvaluationCatalog;
use CakePhpAgent\Evaluation\EvaluationFilter;
use CakePhpAgent\Evaluation\EvaluationRunner;
use CakePhpAgent\Evaluation\HeuristicScorer;
use CakePhpAgent\Evaluation\ScoreResult;
use CakePhpAgent\Evaluation\SelfCheckResponseFactory;
use CakePhpAgent\Filesystem\Filesystem;
use CakePhpAgent\Test\TestTemp;
use PHPUnit\Framework\TestCase;

final class EvaluationRunnerTest extends TestCase
{
    public function testCatalogLoadsPackageEvaluationsDeterministically(): void
    {
        $cases = (new EvaluationCatalog())->load();
        self::assertNotEmpty($cases);

        $ids = array_map(static fn ($c) => $c->id, $cases);
        $sorted = $ids;
        sort($sorted, SORT_STRING);
        self::assertSame($sorted, $ids);
        self::assertSame($ids, array_values(array_unique($ids)));
    }

    public function testFilterByCategory(): void
    {
        $cases = (new EvaluationCatalog())->load(new EvaluationFilter(categories: ['anti-laravel']));
        self::assertNotEmpty($cases);
        foreach ($cases as $case) {
            self::assertSame('anti-laravel', $case->category);
        }
    }

    public function testHeuristicScorerPassAndFail(): void
    {
        $cases = (new EvaluationCatalog())->load(new EvaluationFilter(
            ids: ['unique-email-uses-application-rule']
        ));
        self::assertCount(1, $cases);
        $case = $cases[0];
        $scorer = new HeuristicScorer();
        $factory = new SelfCheckResponseFactory();

        $pass = $scorer->score($case, $factory->passing($case));
        $fail = $scorer->score($case, $factory->failing($case));

        self::assertTrue($pass->passed());
        self::assertFalse($fail->passed());
        self::assertSame(ScoreResult::FAIL, $fail->status);
    }

    public function testRunnerSelfCheckAndBaselineRoundTrip(): void
    {
        $dir = TestTemp::dir('eval-baseline');
        try {
            $runner = new EvaluationRunner();
            $filter = new EvaluationFilter(categories: ['anti-laravel']);
            $run = $runner->run($filter);
            self::assertTrue($run['self_check_ok'], 'anti-laravel self-check should pass');
            self::assertNotEmpty($run['cases']);

            $baselinePath = $dir . '/baseline.json';
            $document = $runner->buildBaseline(
                $run['cases'],
                $run['results'],
                '0.1.0',
                'self-check',
                '1',
            );
            $runner->baselineStore()->write($baselinePath, $document);
            self::assertFileExists($baselinePath);

            $loaded = $runner->baselineStore()->read($baselinePath);
            self::assertSame(1, $loaded['schema_version']);
            self::assertSame($run['fingerprint'], $loaded['catalog']['fingerprint']);

            $compare = $runner->compareBaseline($loaded, $run['cases'], $run['results']);
            self::assertTrue($compare['ok']);
            self::assertSame([], $compare['regressions']);
        } finally {
            (new Filesystem())->remove($dir);
        }
    }

    public function testBaselineDetectsMissingEvaluation(): void
    {
        $runner = new EvaluationRunner();
        $filter = new EvaluationFilter(categories: ['anti-laravel']);
        $run = $runner->run($filter);
        $baseline = $runner->buildBaseline($run['cases'], $run['results'], '0.1.0', 'self-check', '1');
        $baseline['catalog']['ids'][] = 'missing-fixture-id';
        $baseline['catalog']['fingerprint'] = 'stale';

        $compare = $runner->compareBaseline($baseline, $run['cases'], $run['results']);
        self::assertFalse($compare['ok']);
        self::assertNotEmpty($compare['regressions']);
    }
}
