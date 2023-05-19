<?php

namespace Tests\Feature\Admin\Category;

use App\Domain\Users\Models\User;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_customer_can_not_access_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('categories.show', 1));

        $response->assertFound()->assertRedirect(route('home'));
    }

    public function test_admin_access_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('categories.show', 1));
        $response->assertOk();
    }

    public function test_update_category(): void
    {
        $newData = [
            'name' => fake()->name(),
            'status' => GeneralStatus::ACTIVE->value
        ];
        $response = $this->actingAs($this->adminUser)->put(route('categories.update', 1), $newData);
        $response->assertSessionHas('success');
        $response->assertRedirect(route('categories.index'));
    }
}
