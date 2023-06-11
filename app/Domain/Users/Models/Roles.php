<?php

namespace App\Domain\Users\Models;

use App\Support\Definitions\Roles as RolesDefinition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @throws \Exception
     */
    public function value(): RolesDefinition
    {
        return match ($this->id) {
            RolesDefinition::CUSTOMER->value => RolesDefinition::CUSTOMER,
            RolesDefinition::ADMIN->value => RolesDefinition::ADMIN,
            default => throw new \Exception('Rol incorrecto!'),
        };
    }
}
