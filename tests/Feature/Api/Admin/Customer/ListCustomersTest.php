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
        $response = $this->actingAs($this->adminUser)->getJson(route('api.customers') . '?page=2');

        $response->assertOk();
    }

    public function test_search(): void
    {
        $response = $this->actingAs($this->adminUser)->getJson(route('api.customers') . '?filter=and');

        $response->assertOk();
    }
}
