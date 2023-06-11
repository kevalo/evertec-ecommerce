<?php

namespace Tests\Feature\Web\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_view(): void
    {
        $user = User::factory()->create();
        $product = Product::first();
        $this->actingAs($user)->get(route('product-detail', $product->slug))->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page->component('ProductDetail')->has('product')->has('category')
            );
    }
}
