<?php

namespace App\Support\Definitions;

enum PaymentMethods: string
{
    case PLACE_TO_PAY = 'place_to_pay';

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
