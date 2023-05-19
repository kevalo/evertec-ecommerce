<?php

namespace Tests\Feature\Admin\Customer;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->admin()->create();
    }

    public function test_form_is_displayed(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('customers.edit', 2));

        $response->assertOk()->assertSee('Editar cliente');
    }

    public function test_customers_update(): void
    {
        $randomName = $this->faker->name();

        $response = $this->actingAs($this->adminUser)->put(
            '/customers/2',
            [
            'name' => $randomName,
            'last_name' => 'New data',
            'phone' => '315389548'
        ]
        );

        $response->assertFound()->assertRedirect(route('customers'));

        $this->assertEquals($randomName, User::find(2)->name);
    }
}
