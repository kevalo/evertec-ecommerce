<?php

namespace App\Support\Actions;

interface Action
{
    public static function execute(array $params): bool|int;
}
