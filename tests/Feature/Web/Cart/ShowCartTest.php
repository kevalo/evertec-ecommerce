<?php

namespace Tests\Feature\Web\Cart;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('cart'))->assertOk()->assertInertia(
            fn (Assert $page) => $page->component('CartDetail')
        );
    }
}
