<?php

namespace Admin\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ToggleCategoryStatusTest extends TestCase
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
        $category = Category::find(1);
        $oldStatus = $category->status;

        $response = $this->actingAs($this->adminUser)->patch(
            route('api.categories.toggleStatus'),
            ['id' => $category->id]
        );
        $response->assertOk()->assertJson(['data' => [__('categories.success_update')]]);

        $this->assertNotEquals($oldStatus, Category::find(1)->status);
    }

    public function test_toggle_status_fake_category(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(route('api.categories.toggleStatus'), ['id' => 9999]);
        $response->assertFound();
    }

    public function test_toggle_status_failed(): void
    {
        DB::table('categories')->where('id', 1)->update(['status' => 3]);
        $response = $this->actingAs($this->adminUser)->patch(route('api.categories.toggleStatus'), ['id' => 1]);
        $response->assertOk()->assertJson(['data' => [__('categories.error_status_update')]]);
    }
}
