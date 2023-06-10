<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Models\Order;
use App\Domain\Products\Models\Product;
use App\Support\Actions\Action;
use App\Support\Definitions\OrderStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreOrder implements Action
{
    /**
     * @param array $params
     * @return bool|int
     */
    public static function execute(array $params): bool|int
    {
        $ids = array_column($params, 'id');
        $products = Product::select('id', 'quantity', 'price', 'status')->whereIn('id', $ids)->get();

        $stockErrors = self::validateStock($products, $params);
        if ($stockErrors) {
            return false;
        }

        $total = 0;
        foreach ($products as $product) {
            $total += $product->price;
        }

        $order = new Order();
        $order->code = Str::random(6) . time();
        $order->status = OrderStatus::CREATED->value;
        $order->total_price = $total;
        $order->delivery_address = 'TODO: implementar';
        $order->user_id = Auth::id();

        if ($order->save()) {
            self::syncProducts($order, $products, $params);
            return $order->id;
        }

        return false;
    }

    private static function syncProducts(Order $order, Collection $products, array $params): void
    {
        $productsData = [];
        foreach ($products as $product) {
            $quantity = $params[$product->id]['amount'];

            $productsData[$product->id] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => $quantity,
                'total' => $quantity * $product->price
            ];
        }
        $order->products()->sync($productsData);
    }

    private static function validateStock(Collection $products, array $params): bool
    {
        $error = false;

        foreach ($products as $product) {
            if ($params[$product->id]['amount'] > $product->quantity) {
                $error = true;
                break;
            }
        }

        return $error;
    }
}
