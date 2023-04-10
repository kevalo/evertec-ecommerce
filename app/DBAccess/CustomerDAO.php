<?php

namespace App\DBAccess;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
            Log::error($e->getMessage(), ['context' => 'Getting the users with role '.Roles::CUSTOMER->value]);
        }

        return $data;
    }

    public function getAllPaginated(string $filter = null): array|\Illuminate\Pagination\LengthAwarePaginator
    {
        $data = [];

        try {
            $data = User::whereHas('role', static function ($roleQuery) {
                $roleQuery->where('id', Roles::CUSTOMER->value);
            });

            if ($filter) {
                $data = $data->where('name', 'like', '%'.$filter.'%')
                    ->orWhere('email', 'like', '%'.$filter.'%')->paginate(5);
            }

            $data = $data->paginate(5);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Getting the users with role '.Roles::CUSTOMER->value]);
        }

        return $data;
    }

    public function updateStatus(int $id): bool
    {
        $res = false;

        try {
            $user = User::find($id);
            if (! $user) {
                return $res;
            }

            $newStatus = match ($user->status) {
                UserStatus::ACTIVE => UserStatus::INACTIVE->value,
                UserStatus::INACTIVE => UserStatus::ACTIVE->value
            };

            $user->status = $newStatus;
            $res = $user->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating user status']);
        }

        return $res;
    }

    /**
     * @param  array  $data array with the following keys: name, last_name, phone
     */
    public function updateBasicData(User $user, array $data): bool
    {
        $res = false;

        try {
            $res = $user->update($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating user information']);
        }

        return $res;
    }
}
