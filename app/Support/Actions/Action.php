<?php

namespace App\Support\Actions;

use Illuminate\Database\Eloquent\Model;

interface Action
{
    public static function execute(array $params): bool|int|Model;
}
