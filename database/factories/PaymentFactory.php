<?php

namespace Database\Factories;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Support\Definitions\PaymentMethods;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_id' =>  fake()->randomNumber(5, true),
            'process_url' => "https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a",
            'status' => PaymentStatus::CREATED->value,
            'payment_type' => PaymentMethods::PLACE_TO_PAY->value,
            'order_id' => 1
        ];
    }
}
