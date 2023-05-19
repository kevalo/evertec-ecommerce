<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\ToogleStatusRequest;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\Roles;
use App\Support\Definitions\UserStatus;
use App\Support\Exceptions\UnsupportedStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
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

        return response()->json(new StandardResource($customersList));
    }


    public function toggleStatus(ToogleStatusRequest $request): JsonResponse
    {
        $params = $request->validated();
        $user = User::find($params['id']);

        try {
            $newStatus = match ($user->status) {
                UserStatus::ACTIVE => UserStatus::INACTIVE->value,
                UserStatus::INACTIVE => UserStatus::ACTIVE->value,
                UserStatus::PENDING => UserStatus::ACTIVE,
                default => throw new UnsupportedStatus(__('customers.error_status_update'))
            };

            $user->status = $newStatus;
            $user->save();

            $responseData = __('customers.success_update');
        } catch (UnsupportedStatus $e) {
            $responseData = $e->getMessage();
            Log::error($e->getMessage(), ['context' => 'Updating customer status', 'value' => $user->status]);
        }

        return response()->json(new StandardResource([$responseData]));
    }
}
