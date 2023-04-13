<?php

namespace Tests\Feature\Admin\Customer;

use App\Models\User;
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

    public function test_customer_can_not_access_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('customers'));

        $response->assertFound()->assertRedirect(route('home'));
    }

    public function test_admin_access_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('customers'));

        $response->assertOk()->assertSee('Clientes');
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
