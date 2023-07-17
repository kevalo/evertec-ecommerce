<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Orders\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use App\Support\Definitions\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::select(
            'orders.id',
            'orders.code',
            'orders.status',
            'orders.created_at',
            'orders.user_id',
            'orders.total_price',
            'users.name as user'
        )->join('users', 'orders.user_id', '=', 'users.id');

        if ($request->user()->role_id === Roles::ADMIN->value) {
            $query = $query->latest('id')->paginate(5);
        } else {
            $query = $query->where('user_id', Auth::id())->latest('id')->paginate(5);
        }

        return response()->json(new StandardResource($query));
    }
}
