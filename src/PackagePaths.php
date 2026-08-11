<?php

declare(strict_types=1);

namespace CakePhpAgent;

final class PackagePaths
{
    public static function root(): string
    {
        return dirname(__DIR__);
    }

    public static function rules(): string
    {
        return self::root() . '/rules';
    }

    public static function skills(): string
    {
        return self::root() . '/skills';
    }

    public static function agents(): string
    {
        return self::root() . '/agents';
    }

    public static function extensions(): string
    {
        return self::root() . '/extensions';
    }

    public static function schemas(): string
    {
        return self::root() . '/schemas';
    }
}
