<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ToogleStatusRequest;
use App\Models\User;
use App\Traits\ApiController;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiController;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): array
    {
        $filtered = $request->has('filter');
        $filter = $request->get('filter');

        $customersList = User::whereHas('role', static function ($roleQuery) {
            $roleQuery->where('id', Roles::CUSTOMER->value);
        })->when($filtered && $filter, static function ($q) use ($filter) {
            $q->where('name', 'like', '%' . $filter . '%')
                ->orWhere('last_name', 'like', '%' . $filter . '%')
                ->orWhere('email', 'like', '%' . $filter . '%');
        })->latest('id')->paginate(5);

        return $this->response($customersList);
    }

    public function toggleStatus(ToogleStatusRequest $request): array
    {
        $params = $request->validated();

        $user = User::find($params['id']);

        $newStatus = match ($user->status) {
            UserStatus::ACTIVE => UserStatus::INACTIVE->value,
            UserStatus::INACTIVE => UserStatus::ACTIVE->value,
            default => throw new \Exception('Estado de usuario no soportado')
        };

        $user->status = $newStatus;
        $responseStatus = $user->save();

        return $this->response('Usuario actualizado', $responseStatus);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): array
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response('No se encontró el cliente', false);
        }

        return $this->response($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
