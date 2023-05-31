<?php

namespace Tests\Feature\Web\Admin\Dashboard;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_customer_can_not_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertFound()->assertRedirect(route('home'));
    }
}
