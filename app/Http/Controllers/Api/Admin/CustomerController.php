<?php

namespace App\Http\Controllers\Api\Admin;

use App\Definitions\Roles;
use App\Definitions\UserStatus;
use App\Exceptions\UnsupportedStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ToogleStatusRequest;
use App\Http\Resources\ApiResource;
use App\Models\User;
use App\Traits\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    use ApiController;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
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

        return response()->json(new ApiResource($customersList));
    }


    public function toggleStatus(ToogleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();

        try {
            $user = User::find($params['id']);

            $newStatus = match ($user->status) {
                UserStatus::ACTIVE => UserStatus::INACTIVE->value,
                UserStatus::INACTIVE => UserStatus::ACTIVE->value,
                default => throw new UnsupportedStatus('Estado de usuario no soportado: ' . $user->status)
            };

            $user->status = $newStatus;
            $user->save();

            $responseData = 'Usuario actualizada';
        } catch (UnsupportedStatus $e) {
            $responseData = 'Error al actualizar el usuario';
            Log::error($e->getMessage(), ['context' => 'Updating customer status']);
        }

        return response()->json(new ApiResource([$responseData]));
    }
}
