<?php

namespace App\DBAccess;

use App\Definitions\Roles;
use App\Models\User;
use Illuminate\Support\Collection;

class CustomerDAO
{
    public function getAll(): Collection
    {
        $data = collect();

        try {

            $data = User::whereHas('role', static function ($roleQuery) {
                $roleQuery->where('id', Roles::CUSTOMER->value);
            })->get();

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $data;
    }
}
