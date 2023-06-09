<?php

namespace App\Http\Controllers\Api;

use App\Domain\Orders\Actions\StoreOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Order\CreateRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(CreateRequest $request): JsonResponse
    {
        $orderId = StoreOrder::execute($request->validated()['products']);
        if ($orderId) {
            return response()->json(['route' => route('orders.show', $orderId), 'clear_cart' => true]);
        }

        return response()->json(['route' => false, 'clear_cart' => false ]);
    }
}
