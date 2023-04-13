<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filtered = $request->has('filter');
        $filter = $request->get('filter');

        return User::whereHas('role', static function ($roleQuery) {
            $roleQuery->where('id', Roles::CUSTOMER->value);
        })->when($filtered && $filter, static function ($q) use ($filter) {
            $q->where('name', 'like', '%' . $filter . '%')
                ->orWhere('last_name', 'like', '%' . $filter . '%')
                ->orWhere('email', 'like', '%' . $filter . '%');
        })->latest('id')->paginate(5);
    }

    public function toggleStatus(Request $request): array
    {
        $params = $request->validate(['id' => ['required', 'numeric', 'exists:users']]);

        $response = ['status' => false];

        try {
            $user = User::find($params['id']);

            if (!$user) {
                return $response;
            }

            $newStatus = match ($user->status) {
                UserStatus::ACTIVE => UserStatus::INACTIVE->value,
                UserStatus::INACTIVE => UserStatus::ACTIVE->value,
                default => throw new \Exception('Estado de usuario no soportado')
            };

            $user->status = $newStatus;
            $response['status'] = $user->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['context' => 'Updating user status']);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
