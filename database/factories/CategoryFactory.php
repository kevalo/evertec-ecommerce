<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => GeneralStatus::ACTIVE->value
        ];
    }
}
