<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function value()
    {
        return match ($this->id) {
            \App\Definitions\Roles::CUSTOMER->value => \App\Definitions\Roles::CUSTOMER,
            \App\Definitions\Roles::ADMIN->value => \App\Definitions\Roles::ADMIN,
            default => throw new \Exception('Rol incorrecto!'),
        };
    }
}
