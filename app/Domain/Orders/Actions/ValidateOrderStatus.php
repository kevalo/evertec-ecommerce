<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Models\Order;
use App\Support\Actions\Action;
use App\Support\Definitions\OrderStatus;

class ValidateOrderStatus implements Action
{
    /**
     * @param array $params
     * @return bool|int
     */
    public static function execute(array $params): bool|int
    {
        $id = $params['order_id'];
        $order = Order::find($id);
        return $order->status === OrderStatus::CREATED->value;
    }
}
