<?php

namespace Tests\Feature\Admin\Customer;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
        $customer = User::find(2);
        $oldStatus = $customer->status;

        $response = $this->actingAs($this->adminUser)->patch(
            route('api.customers.toggleStatus'),
            ['id' => $customer->id]
        );
        $response->assertOk()->assertJson(['data' => [__('customers.success_update')]]);

        $this->assertNotEquals($oldStatus, User::find(2)->status);
    }

    public function test_toggle_status_fake_customer(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(route('api.customers.toggleStatus'), ['id' => 9999]);
        $response->assertFound();
    }

    public function test_toggle_status_failed(): void
    {
        DB::table('users')->where('id', 1)->update(['status' => 6]);
        $response = $this->actingAs($this->adminUser)->patch(route('api.customers.toggleStatus'), ['id' => 1]);
        $response->assertOk()->assertJson(['data' => [__('customers.error_status_update')]]);
    }
}
