<?php

namespace Tests\Feature\Admin\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ToggleProductStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_toggle_status(): void
    {
        $product = Product::find(1);
        $oldStatus = $product->status;

        $response = $this->actingAs($this->adminUser)->patch(
            route('api.admin.products.toggleStatus'),
            ['id' => $product->id]
        );
        $response->assertOk()->assertJson(['data' => [__('products.success_update')]]);

        $this->assertNotEquals($oldStatus, Product::find($product->id)->status);
    }

    public function test_toggle_status_fake_product(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(route('api.admin.products.toggleStatus'), ['id' => 9999]);
        $response->assertFound();
    }

    public function test_toggle_status_failed(): void
    {
        DB::table('products')->where('id', 1)->update(['status' => 6]);
        $response = $this->actingAs($this->adminUser)->patch(route('api.admin.products.toggleStatus'), ['id' => 1]);
        $response->assertOk()->assertJson(['data' => [__('products.error_status_update')]]);
    }
}
