<?php

namespace App\Enums;

enum Service: int
{
    case Push = 1;
    // case Email = 2;

    public static function getAll(): array
    {
        return array_reduce(self::cases(), function ($array, $case) {
            return $array + [$case->name => $case->value];
        }, []);
    }

    public static function getNameByValue(int $type): string
    {
        return self::tryFrom($type)?->name;
    }
}
