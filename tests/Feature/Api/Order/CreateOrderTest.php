<?php

namespace Api\Order;

use App\Domain\Users\Models\User;
use App\Support\Definitions\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_create_order(): void
    {
        $this->post(route('api.orders.store'))
            ->assertFound()->assertRedirect(route('login'));
    }

    public function test_admin_can_not_create_order(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)->post(route('api.orders.store'))
            ->assertForbidden();
    }

    public function test_customer_can_create_order(): void
    {
        $user = User::factory()->create();
        $resp = $this->actingAs($user)->post(
            route('api.orders.store'),
            [
                'products' => [
                    1 => ['id' => 1, 'amount' => 1],
                    2 => ['id' => 2, 'amount' => 3]
                ]
            ]
        );

        $order = $user->orders->first();

        $resp->assertSessionDoesntHaveErrors()
            ->assertOk()
            ->assertJson(['route' => route('orders.show', $order->id), 'clear_cart' => true]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => OrderStatus::CREATED->value
        ]);

        $this->assertDatabaseHas('order_products', ['product_id' => 1, 'order_id' => $order->id, 'quantity' => 1])
            ->assertDatabaseHas('order_products', ['product_id' => 2, 'order_id' => $order->id, 'quantity' => 3]);
    }
}
