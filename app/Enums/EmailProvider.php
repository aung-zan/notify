<?php

namespace App\Enums;

enum EmailProvider: int
{
    case Mailtrap = 1;
    case SMTP = 2;
    case Amazon_SES = 3;

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
