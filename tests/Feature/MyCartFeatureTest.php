<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartLineItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class MyCartFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldShowCartListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/my/cart');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainUserSignedInCartOnCartListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();

        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->has(CartLineItem::factory(2), 'lineItems')
            ->create();

        $response = $this->actingAs($user)->get('/my/cart');

        $response->assertSee([
            $cart->formatted_quantity,
            $cart->formatted_total_price,
            $cart->lineItems[0]->name,
            $cart->lineItems[0]->formatted_price,
            $cart->lineItems[0]->formatted_quantity,
            $cart->lineItems[0]->formatted_total_price,
        ]);
    }
}
