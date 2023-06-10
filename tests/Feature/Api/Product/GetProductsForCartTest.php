<?php

namespace Api\Product;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetProductsForCartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_access_list(): void
    {
        $p1 = Product::find(1);
        $p2 = Product::find(2);
        $p3 = Product::find(3);

        $this->actingAs($this->user)
            ->post(route('api.getCartProducts'), ['ids' => [$p1->id, $p2->id, $p3->id]])
            ->assertOk()
            ->assertSimilarJson([
                'data' => [
                    [
                        'id' => $p1->id,
                        'name' => $p1->name,
                        'slug' => $p1->slug,
                        'image' => $p1->image,
                        'price' => $p1->price,
                        'quantity' => $p1->quantity
                    ],
                    [
                        'id' => $p2->id,
                        'name' => $p2->name,
                        'slug' => $p2->slug,
                        'image' => $p2->image,
                        'price' => $p2->price,
                        'quantity' => $p2->quantity
                    ],
                    [
                        'id' => $p3->id,
                        'name' => $p3->name,
                        'slug' => $p3->slug,
                        'image' => $p3->image,
                        'price' => $p3->price,
                        'quantity' => $p3->quantity
                    ]
                ]
            ]);
    }
}
