<?php

namespace Tests\Feature\Web\Admin\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateProductTest extends TestCase
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

        $response = $this->actingAs($user)->get(route('products.create'));

        $response->assertFound()->assertRedirect(route('home'));
    }

    public function test_admin_access_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('products.create'));
        $response->assertOk()->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Product/Create'));
    }

    public function test_save_product(): void
    {
        $current = Product::count();

        $newProduct = Product::factory()->make()->attributesToArray();
        $newProduct['status'] = GeneralStatus::ACTIVE->value;
        $newProduct['image'] = UploadedFile::fake()->image('new_product_image.png', 640, 480);

        $response = $this->actingAs($this->adminUser)->post(route('products.store'), $newProduct);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('products.index'));

        $this->assertEquals(Product::count(), $current+ 1);
    }
}
