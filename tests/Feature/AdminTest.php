<?php

namespace Tests\Feature;

use App\Definitions\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_dashboard_page_is_displayed(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_customers_page_is_displayed(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->get('/customers');

        $response->assertStatus(200);
        $response->assertSee('Clientes');
    }

    public function test_customers_pagination_is_displayed(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->get('/customers?page=2');

        $response->assertStatus(200);
    }

    public function test_customers_table_filter(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->get('/customers?filter=and');

        $response->assertStatus(200);
        $response->assertJson(['customers' => []]);
    }

    public function test_customers_update_is_displayed(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->get('/customers/2');

        $response->assertStatus(200);
        $response->assertSee('Editar cliente');
    }

    public function test_customers_toggle_status(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->patch('/toggleCustomerStatus', ['id' => 2]);

        $response->assertStatus(200);
        $response->assertJson(['status' => true]);
    }

    public function test_customers_toggle_status_failed(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $response = $this
            ->actingAs($user)
            ->patch('/toggleCustomerStatus', ['id' => 9999]);

        $response->assertStatus(302);
    }

    public function test_customers_update(): void
    {
        $user = User::factory()->create(['role_id' => Roles::ADMIN->value]);

        $randomName = $this->faker->name();

        $response = $this
            ->actingAs($user)
            ->put('/customers/2', ['name' => $randomName, 'last_name' => 'New data', 'phone' => '315389548']);

        $response->assertStatus(302);
        $response->assertRedirect('/customers');

        $this->assertEquals($randomName, User::find(2)->name);
    }
}
