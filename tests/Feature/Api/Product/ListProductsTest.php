<?php

namespace Tests\Feature\Api\Product;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_access_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api.products') .'?filter=and&category=1');
        $response->assertOk();
    }
}
