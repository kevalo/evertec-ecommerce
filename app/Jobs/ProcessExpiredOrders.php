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
                ->where('status', OrderStatus::CREATED->value)
                ->whereRaw(
                    'TIMESTAMPDIFF(MINUTE, orders.created_at, UTC_TIMESTAMP) > ?',
                    config('constants.orders_expire_minutes')
                )
                ->get();

            DB::table('orders')
                ->whereIn('id', $orders->pluck('id')->toArray())
                ->update(['status' => OrderStatus::CANCELED->value]);

            foreach ($orders as $order) {
                $products = DB::table('order_products')
                    ->select('product_id', 'quantity')
                    ->where('order_id', $order->id)->get();

                foreach ($products as $product) {
                    DB::table('products')
                        ->where('id', $product->product_id)
                        ->increment('quantity', $product->quantity);
                }
            }
        });
    }
}
