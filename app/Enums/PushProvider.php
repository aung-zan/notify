<?php

namespace App\Enums;

enum PushProvider: int
{
    case Pusher = 1;
    case Firebase = 2;
    // case RabbitMQ = 3;

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
