<?php

namespace App\Http\Controllers\Api;

use App\Domain\Orders\Actions\StoreOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CreateRequest;
use App\Http\Resources\Api\StandardResource;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(CreateRequest $request): JsonResponse
    {
        $orderId = StoreOrder::execute($request->validated()['products']);
        if ($orderId) {
            return response()
                ->json(new StandardResource(['route' => route('orders.show', $orderId), 'clear_cart' => true]));
        }

        return response()->json(new StandardResource(['route' => false, 'clear_cart' => false]));
    }
}
