<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $name = fake()->domainName();

        return [
            'name' => $name,
            'description' => fake()->sentence(100),
            'slug' => Str::slug($name, '-', 'es'),
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
