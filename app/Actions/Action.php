<?php

namespace App\Actions;

interface Action
{
    public static function execute(array $params): bool;
}
