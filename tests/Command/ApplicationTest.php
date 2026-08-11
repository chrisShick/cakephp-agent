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
    }

    public function testUnknownCommandExitsOne(): void
    {
        ob_start();
        $code = (new Application())->run(['cakephp-agent', 'nope']);
        ob_end_clean();

        self::assertSame(1, $code);
    }
}
