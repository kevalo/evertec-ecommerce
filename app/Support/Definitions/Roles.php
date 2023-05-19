<?php

namespace App\Support\Definitions;

enum Roles: int
{
    case ADMIN = 1;
    case CUSTOMER = 2;

    public static function toArray(): array
    {
        $cases = self::cases();
        $array = [];

        foreach ($cases as $c) {
            $array[] = ['id' => $c->value, 'name' => strtolower($c->name)];
        }

        return $array;
    }
}
