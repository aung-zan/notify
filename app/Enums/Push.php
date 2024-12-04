<?php

namespace App\Enums;

enum Push: int
{
    case Pusher = 11;
    case Firebase = 12;
    case RabbitMQ = 13;

    public static function getAll(): array
    {
        return array_reduce(self::cases(), function ($array, $case) {
            return $array + [$case->name => $case->value];
        }, []);
    }
}
