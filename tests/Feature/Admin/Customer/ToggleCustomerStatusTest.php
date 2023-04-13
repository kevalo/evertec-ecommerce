<?php

namespace Tests\Feature\Admin\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleCustomerStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_toggle_status(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(route('api.customers.toggleStatus'), ['id' => 2]);

        $response->assertOk()->assertJson(['status' => true]);
    }

    public function test_toggle_status_failed(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(route('api.customers.toggleStatus'), ['id' => 9999]);

        $response->assertFound();
    }
}
