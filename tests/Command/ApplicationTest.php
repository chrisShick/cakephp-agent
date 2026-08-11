<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Command;

use CakePhpAgent\Command\Application;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function testHelpExitsZero(): void
    {
        ob_start();
        $code = (new Application())->run(['cakephp-agent', 'help']);
        $output = ob_get_clean();

        self::assertSame(0, $code);
        self::assertStringContainsString('CakePHP Agent', (string) $output);
        self::assertStringContainsString('install', (string) $output);
        self::assertStringContainsString('eval', (string) $output);
    }

    public function testUnknownCommandExitsOne(): void
    {
        ob_start();
        $code = (new Application())->run(['cakephp-agent', 'nope']);
        ob_end_clean();

        self::assertSame(1, $code);
    }

    public function testEvalSelfCheckExitsZero(): void
    {
        ob_start();
        $code = (new Application())->run(['cakephp-agent', 'eval', '--category=anti-laravel']);
        $output = ob_get_clean();

        self::assertSame(0, $code);
        self::assertStringContainsString('Self-check: OK', (string) $output);
        self::assertStringContainsString('anti-laravel', (string) $output);
    }

    public function testEvalJsonFormat(): void
    {
        ob_start();
        $code = (new Application())->run([
            'cakephp-agent',
            'eval',
            '--category=anti-laravel',
            '--format=json',
        ]);
        $output = ob_get_clean();

        self::assertSame(0, $code);
        $data = json_decode((string) $output, true);
        self::assertIsArray($data);
        self::assertTrue($data['self_check_ok']);
        self::assertGreaterThan(0, $data['catalog']['count']);
    }
}
