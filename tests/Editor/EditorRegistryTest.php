<?php

declare(strict_types=1);

namespace CakePhpAgent\Test\Editor;

use CakePhpAgent\Editor\EditorRegistry;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EditorRegistryTest extends TestCase
{
    public function testResolvesBuiltInEditors(): void
    {
        $registry = new EditorRegistry();

        self::assertSame('cursor', $registry->get('cursor')->id());
        self::assertSame('claude', $registry->get('claude')->id());
        self::assertSame('codex', $registry->get('codex')->id());
        self::assertNull($registry->get('codex')->agentsDirectory('/tmp/app'));
    }

    public function testUnknownEditorThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new EditorRegistry())->get('notepad');
    }
}
