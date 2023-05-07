<?php

namespace Tests\Feature\Admin\Product;

use App\Definitions\GeneralStatus;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_customer_can_not_access_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('products.show', 1));

        $response->assertFound()->assertRedirect(route('home'));
    }

    public function test_admin_access_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('products.show', 1));
        $response->assertOk();
    }

    public function test_update_product_without_image(): void
    {
        $newData = [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'status' => GeneralStatus::INACTIVE->value,
            'price' => fake()->randomNumber(4),
            'category_id' => Category::first()->id,
            'image' => null
        ];
        $response = $this->actingAs($this->adminUser)->put(route('products.update', 1), $newData);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('products.index'));
    }

    public function test_update_product_with_image(): void
    {
        $newData = [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'status' => GeneralStatus::INACTIVE->value,
            'price' => fake()->randomNumber(4),
            'category_id' => Category::first()->id,
            'image' => UploadedFile::fake()->image('product_image.png', 640, 480)
        ];
        $response = $this->actingAs($this->adminUser)->put(route('products.update', 1), $newData);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('products.index'));
    }
}
