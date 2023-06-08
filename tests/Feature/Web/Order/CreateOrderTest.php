<?php

namespace Tests\Feature\Web\Order;

use App\Domain\Users\Models\User;
use App\Support\Definitions\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_create_order(): void
    {
        $this->post(route('orders.store'))
            ->assertFound()->assertRedirect(route('login'));
    }

    public function test_admin_can_not_create_order(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user)->post(route('orders.store'))
            ->assertForbidden();
    }

    public function test_customer_can_create_order(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(
            route('orders.store'),
            [
                'products' => [
                    1 => ['id' => 1, 'amount' => 1],
                    2 => ['id' => 2, 'amount' => 3]
                ]
            ]
        )->assertSessionDoesntHaveErrors()
            ->assertFound()
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => OrderStatus::CREATED->value
        ]);

        $order = $user->orders->first();

        $this->assertDatabaseHas('order_products', ['product_id' => 1, 'order_id' => $order->id, 'quantity' => 1])
            ->assertDatabaseHas('order_products', ['product_id' => 2, 'order_id' => $order->id, 'quantity' => 3]);
    }
}
