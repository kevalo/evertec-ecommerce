<?php

namespace Tests\Feature\Web\Order;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Users\Models\User;
use App\Support\Definitions\PaymentMethods;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\PaymentFactory;
use App\Support\Services\PlaceToPayPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class ShowOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_orders_list_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('orders.index'))->assertOk()->assertInertia(
            fn (Assert $page) => $page->component('Admin/Order/List')
        );
    }

    public function test_guest_can_not_view_order(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $this->get(route('orders.show', $order->id))
            ->assertRedirect(route('login'));
    }

    public function test_other_user_can_not_view_order(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)->get(route('orders.show', $order->id))
            ->assertRedirect(route('home'));
    }

    public function test_view_order_without_payment(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->get(route('orders.show', $order->id))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page->component('Order/Detail')
                    ->has('order')->has('products')->has('status')->has('newPayment')->has('currentPaymentUrl')
            );
    }

    public function test_view_order_wit_payment_created(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        Payment::factory()->for($order)->create();

        $this->actingAs($this->user)->get(route('orders.show', $order->id))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page->component('Order/Detail')
                    ->has('order')->has('products')->has('status')->has('newPayment')->has('currentPaymentUrl')
            );
    }

    public function test_view_order_wit_payment_approved(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $payment = Payment::factory()->for($order)->create();

        $placeToPayMock = $this->mock(PlaceToPayPayment::class, function (MockInterface $mock) use ($payment) {
            $mock->shouldReceive('getPaymentStatus')
                ->withSomeOfArgs((string)$payment->request_id)
                ->andReturn(PaymentStatus::APPROVED->value);
        });

        $this->mock(PaymentFactory::class, function (MockInterface $mock) use ($placeToPayMock) {
            $mock->shouldReceive('initializePayment')
                ->withSomeOfArgs(PaymentMethods::PLACE_TO_PAY->value)
                ->andReturn($placeToPayMock);
        });

        $this->actingAs($this->user)->get(route('orders.show', $order->id))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page->component('Order/Detail')
                    ->has('order')->has('products')->has('status')->has('newPayment')->has('currentPaymentUrl')
            );
    }
}
