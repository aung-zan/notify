<?php

namespace App\Enums;

enum Push: int
{
    case Pusher = 4;
    case Firebase = 5;
    case RabbitMQ = 6;

    public static function getAll(): array
    {
        return array_reduce(self::cases(), function ($array, $case) {
            return $array + [$case->name => $case->value];
        }, []);
    }
}
