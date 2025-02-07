<?php

namespace App\Enums;

enum Service: string
{
    case Push = 'push';
    case Email = 'email';

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
