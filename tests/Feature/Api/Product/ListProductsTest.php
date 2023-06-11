<?php

namespace Tests\Feature\Api\Product;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('api.products') .'?filter=and&category=1');
        $response->assertOk();
    }

    public function test_pagination(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('api.products') . '?page=2');
        $response->assertOk();
    }

    public function test_search(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('api.products') . '?filter=and&category=1');
        $response->assertOk();
    }
}
