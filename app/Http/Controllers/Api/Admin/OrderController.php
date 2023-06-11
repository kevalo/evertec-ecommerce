<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Orders\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            new StandardResource(
                Order::select('orders.id', 'orders.code', 'orders.status', 'orders.created_at', 'orders.user_id', 'orders.total_price', 'users.name as user')
                    ->where('user_id', Auth::id())
                    ->join('users', 'orders.user_id', '=', 'users.id')
                    ->latest('id')->paginate(5)
            )
        );
    }
}
