<?php

namespace App\Jobs;

use App\Support\Definitions\OrderStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessExpiredOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        DB::transaction(static function () {
            $orders = DB::table('orders')
                ->select(['orders.id', 'orders.status', 'order_products.product_id', 'order_products.quantity'])
                ->join('order_products', 'order_products.order_id', '=', 'orders.id')
                ->where('orders.status', OrderStatus::CREATED->value)
                ->whereRaw(
                    'TIMESTAMPDIFF(MINUTE, orders.created_at, UTC_TIMESTAMP) > ?',
                    config('constants.orders_expire_minutes')
                )
                ->get();

            DB::table('orders')
                ->whereIn('id', $orders->pluck('id')->unique()->toArray())
                ->update(['status' => OrderStatus::CANCELED->value]);

            foreach ($orders as $order) {
                DB::table('products')
                    ->where('id', $order->product_id)
                    ->increment('quantity', $order->quantity);
            }
        });
    }
}
