<?php

namespace Tests\Feature\Web\Admin\Category;

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

    public function test_customer_can_not_access_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('categories.index'));

        $response->assertFound()->assertRedirect(route('home'));
    }

    public function test_admin_access_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('categories.index'));
        $response->assertOk();
    }
}
