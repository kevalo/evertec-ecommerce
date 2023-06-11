<?php

namespace Tests\Feature\Web\Payment;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentMethods;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->baseUrl = config('services.placetopay.baseUrl');
    }

    public function test_guest_can_not_create_payment(): void
    {
        $this->post(
            route('payment.create'),
            [
                'order_id' => 1,
                'payment_type' => PaymentMethods::PLACE_TO_PAY->value
            ]
        )->assertRedirect(route('login'));
    }

    public function test_order_is_canceled(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::CANCELED->value, 'user_id' => $this->user->id]);
        $this->actingAs($this->user)->post(
            route('payment.create'),
            [
                'order_id' => $order->id,
                'payment_type' => PaymentMethods::PLACE_TO_PAY->value
            ]
        )->assertRedirect(route('orders.index'));
    }

    public function test_payment_is_created(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $url = "https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a";

        $responseMock = [
            "status" => [
                "status" => "OK",
                "reason" => "PC",
                "message" => "La petición se ha procesado correctamente",
                "date" => "2021-11-30T15:08:27-05:00"
            ],
            "requestId" => 1,
            "processUrl" => $url
        ];

        Http::fake([$this->baseUrl . '/session' => $responseMock]);

        $this->actingAs($this->user)->post(
            route('payment.create'),
            [
                'order_id' => $order->id,
                'payment_type' => PaymentMethods::PLACE_TO_PAY->value
            ]
        )->assertRedirect($url);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => PaymentStatus::CREATED->value,
            'process_url' => $url
        ]);

        $this->assertEquals(1, $order->payments->count());
    }
}
