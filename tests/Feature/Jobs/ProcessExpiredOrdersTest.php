<?php

namespace Jobs;

use App\Domain\Orders\Models\Order;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Jobs\ProcessExpiredOrders;
use App\Support\Definitions\OrderStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessExpiredOrdersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_expired_order_is_canceled(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $order = Order::factory()
            ->hasAttached(
                $product,
                [
                    'price' => $product->price,
                    'quantity' => 5,
                    'total' => 5 * $product->price
                ]
            )
            ->create([
                'user_id' => $this->user->id,
                'created_at' => Carbon::now()->subHour()->getTimestamp()
            ]);

        $job = new ProcessExpiredOrders();
        $job->handle();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CANCELED->value
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 15
        ]);
    }

    public function test_active_order_is_not_canceled(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $order = Order::factory()
            ->hasAttached(
                $product,
                [
                    'price' => $product->price,
                    'quantity' => 5,
                    'total' => 5 * $product->price
                ]
            )
            ->create([
                'user_id' => $this->user->id,
                'created_at' => Carbon::now()->getTimestamp()
            ]);

        $job = new ProcessExpiredOrders();
        $job->handle();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::CREATED->value
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'quantity' => 10
        ]);
    }
}
