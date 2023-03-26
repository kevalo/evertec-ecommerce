<?php

namespace App\DBAccess;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
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

    public function updateStatus(int $id): bool
    {
        $res = false;

        try {

            $user = User::find($id);
            if (!$user) {
                return $res;
            }

            $newStatus = match ($user->status) {
                UserStatus::ACTIVE => UserStatus::INACTIVE->value,
                UserStatus::INACTIVE => UserStatus::ACTIVE->value
            };

            $user->status = $newStatus;
            $res = $user->save();

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $res;
    }
}
