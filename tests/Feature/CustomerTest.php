<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_dashboard_redirection(): void
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
