<?php

namespace Api\Product;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckProductQuantityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_amount_in_stock(): void
    {
        $this->actingAs($this->user)
            ->post(route('api.products.checkStock'), ['id' => 1, 'amount' => 1])
            ->assertOk()
            ->assertJson(['data' => ['stock' => true]]);
    }

    public function test_amount_not_in_stock(): void
    {
        $this->actingAs($this->user)
            ->post(route('api.products.checkStock'), ['id' => 1, 'amount' => 500])
            ->assertOk()
            ->assertJson(['data' => ['stock' => false]]);
    }
}
