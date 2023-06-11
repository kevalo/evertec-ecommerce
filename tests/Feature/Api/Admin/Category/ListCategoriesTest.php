<?php

namespace Api\Admin\Category;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCategoriesTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_pagination(): void
    {
        $response = $this->actingAs($this->adminUser)->getJson(route('api.categories') . '?page=1');
        $response->assertOk()->assertJson(["data" => ["current_page" => 1 ]]);
        $this->assertEquals(10, $response->json()["data"]["data"][0]['id']);

        $response = $this->actingAs($this->adminUser)->getJson(route('api.categories') . '?page=2');
        $response->assertOk()->assertJson(["data" => ["current_page" => 2 ]]);
        $this->assertEquals(5, $response->json()["data"]["data"][0]['id']);
    }
}
