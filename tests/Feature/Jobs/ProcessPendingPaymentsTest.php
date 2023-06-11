<?php

namespace Jobs;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Jobs\ProcessPendingPayments;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentMethods;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\PaymentFactory;
use App\Support\Services\PlaceToPayPayment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class ProcessPendingPaymentsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_payment_gets_approved(): void
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

        $payment = Payment::factory()
            ->for($order)
            ->create();

        $placeToPayMock = $this->mock(PlaceToPayPayment::class, function (MockInterface $mock) use ($payment) {
            $mock->shouldReceive('getPaymentStatus')
                ->withSomeOfArgs((string)$payment->request_id)
                ->andReturn(PaymentStatus::APPROVED->value);
        });

        $paymentFatoryMock = $this->mock(PaymentFactory::class, function (MockInterface $mock) use ($placeToPayMock) {
            $mock->shouldReceive('initializePayment')
                ->withSomeOfArgs(PaymentMethods::PLACE_TO_PAY->value)
                ->andReturn($placeToPayMock);
        });

        $job = new ProcessPendingPayments();
        $job->handle($paymentFatoryMock);

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => PaymentStatus::APPROVED->value]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => OrderStatus::COMPLETED->value]);
    }

    public function test_payment_gets_rejected(): void
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

        $payment = Payment::factory()
            ->for($order)
            ->create();

        $placeToPayMock = $this->mock(PlaceToPayPayment::class, function (MockInterface $mock) use ($payment) {
            $mock->shouldReceive('getPaymentStatus')
                ->withSomeOfArgs((string)$payment->request_id)
                ->andReturn(PaymentStatus::REJECTED->value);
        });

        $paymentFatoryMock = $this->mock(PaymentFactory::class, function (MockInterface $mock) use ($placeToPayMock) {
            $mock->shouldReceive('initializePayment')
                ->withSomeOfArgs(PaymentMethods::PLACE_TO_PAY->value)
                ->andReturn($placeToPayMock);
        });

        $job = new ProcessPendingPayments();
        $job->handle($paymentFatoryMock);

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => PaymentStatus::REJECTED->value]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => OrderStatus::CREATED->value]);
    }
}
