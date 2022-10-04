<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartLineItem;
use App\Models\Product;
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

    /**
     * @test
     */
    public function shouldSuccessAddProductToCart(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()->build();

        $response = $this->actingAs($user)->post('/my/cart', [
            'product_id' => $product->id,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'quantity' => 1,
            'total_price' => $product->price,
        ]);

        $this->assertDatabaseHas('cart_line_items', [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => 1,
            'total_price' => $product->price,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessAddProductToCartWithSpecifiedQuantity(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()->build();

        $response = $this->actingAs($user)->post('/my/cart', [
            'product_id' => $product->id,
            'quantity' => 5
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'quantity' => 5,
            'total_price' => $product->price * 5,
        ]);

        $this->assertDatabaseHas('cart_line_items', [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => 5,
            'total_price' => $product->price * 5,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessAddProductToExistingCart(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product1 = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product1, 1);

        /** @var Product */
        $product2 = Product::factory()->create();
        $response = $this->actingAs($user)->post('/my/cart', [
            'product_id' => $product2->id,
            'quantity' => 4,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
            'quantity' => 5,
            'total_price' => ($product1->price) + ($product2->price * 4),
        ]);

        $this->assertDatabaseHas('cart_line_items', [
            'product_id' => $product2->id,
            'sku' => $product2->sku,
            'price' => $product2->price,
            'quantity' => 4,
            'total_price' => $product2->price * 4,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessIncrementLineItemQuantityToExistingCart(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product, 1);

        $response = $this->actingAs($user)->post('/my/cart', [
            'product_id' => $product->id,
            'quantity' => 4,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
            'quantity' => 5,
            'total_price' => $product->price * 5,
        ]);

        $this->assertDatabaseHas('cart_line_items', [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => 5,
            'total_price' => $product->price * 5,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessUpdateLineItemQuantity(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product, 1);

        $response = $this->actingAs($user)->put('/my/cart', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
            'quantity' => 3,
            'total_price' => $product->price * 3,
        ]);

        $this->assertDatabaseHas('cart_line_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => 3,
            'total_price' => $product->price * 3,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessDeleteLineItem(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product1 = Product::factory()->create();
        /** @var Product */
        $product2 = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product1, 1);
        $cart->addLineItem($product2, 1);

        $response = $this->actingAs($user)->delete('/my/cart', [
            'product_id' => $product1->id,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'user_id' => $user->id,
            'quantity' => 1,
            'total_price' => $product2->price,
        ]);

        $this->assertDatabaseMissing('cart_line_items', [
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessDeleteLineItemAndDeleteCart(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product, 1);

        $response = $this->actingAs($user)->delete('/my/cart', [
            'product_id' => $product->id,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseMissing('carts', [
            'id' => $cart->id,
        ]);

        $this->assertDatabaseMissing('cart_line_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }
}
