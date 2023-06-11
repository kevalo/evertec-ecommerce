<?php

namespace Api\Admin\Customer;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCustomersTest extends TestCase
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
        $response = $this->actingAs($this->adminUser)->getJson(route('api.customers') . '?page=1');
        $response->assertOk()->assertJson(["data" => ["current_page" => 1 ]]);
        $this->assertEquals(400, $response->json()["data"]["data"][0]['id']);

        $response = $this->actingAs($this->adminUser)->getJson(route('api.customers') . '?page=2');
        $response->assertOk()->assertJson(["data" => ["current_page" => 2 ]]);
        $this->assertEquals(395, $response->json()["data"]["data"][0]['id']);
    }

    public function test_search(): void
    {
        $user = User::factory()->create(['name' => 'Kevin Hernan']);
        $response = $this->actingAs($this->adminUser)->getJson(route('api.customers') . '?filter=Kevin Hernan');
        $response->assertOk()->assertJson(['data' => ['current_page' => 1]]);
        $this->assertEquals($response->json()['data']['data'][0]['name'], $user->name);
    }
}
