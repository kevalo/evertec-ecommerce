<?php

namespace App\Support\Definitions;

enum GeneralStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

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
