<?php

namespace Api\Admin\Product;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_access_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('api.admin.products') .'?filter=and&category=1');
        $response->assertOk();
    }

    public function test_pagination(): void
    {
        $response = $this->actingAs($this->adminUser)->getJson(route('api.admin.products') . '?page=2');
        $response->assertOk();
    }

    public function test_search(): void
    {
        $response = $this->actingAs($this->adminUser)->getJson(route('api.admin.products') . '?filter=and&category=1');
        $response->assertOk();
    }
}
