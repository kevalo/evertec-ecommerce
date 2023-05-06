<?php

namespace Database\Factories;

use App\Definitions\GeneralStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (env('APP_ENV') === 'testing') {
            Storage::fake('public');
        }

        return [
            'name' => fake()->domainName(),
            'description' => fake()->sentence(100),
            'slug' => fake()->slug(),
            'image' => Storage::disk('public')->put(
                'products_images',
                UploadedFile::fake()->image('image.png', 640, 480)
            ),
            'price' => fake()->randomNumber(4),
            'quantity' => fake()->randomNumber(2),
            'status' => GeneralStatus::ACTIVE->value,
            'category_id' => fake()->randomElement(Category::all())['id']
        ];
    }
}
