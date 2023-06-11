<?php

namespace Tests\Feature\Web\Admin\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AddQuantityProductTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_can_access_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('products.add', 1));
        $response->assertOk()->assertInertia(
            fn (AssertableInertia $page) => $page->component('Admin/Product/AddQuantity')->has('product')
        );
    }

    public function test_add_quantity(): void
    {
        $product = Product::find(1);
        $response = $this->actingAs($this->adminUser)->patch(route('products.add', $product->id), ['quantity' => 3]);
        $response->assertFound();
        $response->assertSessionHas('success');
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => $product->quantity + 3]);
    }
}
