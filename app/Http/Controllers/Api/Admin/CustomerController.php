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

        return response()->json(new ApiResource([$responseData]));
    }
}
