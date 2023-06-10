<?php

namespace App\Support\Definitions;

enum OrderStatus: string
{
    case CREATED = 'created';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $c) {
            $array[strtolower($c->name)] = $c->value;
        }

        return $array;
    }
}
