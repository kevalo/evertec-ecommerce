<?php

namespace App\Definitions;

enum GeneralStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public static function toJson(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $c) {
            $array[strtolower($c->name)] = $c->value;
        }

        return $array;
    }
}
