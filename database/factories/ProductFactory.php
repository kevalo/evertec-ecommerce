<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

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
            'price' => fake()->numberBetween(1000, 1000000),
            'quantity' => fake()->numberBetween(10, 100),
            'status' => GeneralStatus::ACTIVE->value,
            'category_id' => fake()->randomElement(Category::all())['id']
        ];
    }
}
