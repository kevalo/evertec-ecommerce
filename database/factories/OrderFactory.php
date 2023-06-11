<?php

namespace Database\Factories;

use App\Domain\Orders\Models\Order;
use App\Support\Definitions\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' =>  Str::random(6) . time(),
            'status' => OrderStatus::CREATED->value,
            'total_price' => fake()->numberBetween(1000, 100000),
            'delivery_address' => fake()->address(),
            'user_id' => 1,
            'created_at' => time()
        ];
    }
}
