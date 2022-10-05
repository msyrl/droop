<?php

namespace Tests\Feature;

use App\Enums\SalesOrderStatusEnum;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class MyCartCheckoutFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldShowCartCheckoutPage()
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

        $response = $this->actingAs($user)->get('/my/cart/checkout');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainCartSummaryOnCartCheckoutPage()
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

        $response = $this->actingAs($user)->get('/my/cart/checkout');

        $response->assertSee([
            $cart->formatted_quantity,
            $cart->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessCheckoutCart(): void
    {
        Storage::fake();

        /** @var User */
        $user = UserBuilder::make()->build();
        /** @var Product */
        $product = Product::factory()->create();
        /** @var Cart */
        $cart = Cart::factory()
            ->for($user)
            ->create();

        $cart->addLineItem($product, 1);

        $response = $this->actingAs($user)->post('/my/cart/checkout', [
            'attachments' => [
                UploadedFile::fake()->create('attachment.pdf'),
            ],
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseMissing('carts', [
            'id' => $cart->id,
        ]);

        $this->assertDatabaseMissing('cart_line_items', [
            'cart_id' => $cart->id,
        ]);

        $this->assertDatabaseHas('sales_orders', [
            'user_id' => $user->id,
            'status' => SalesOrderStatusEnum::waiting(),
            'paid' => false,
            'quantity' => $cart->quantity,
            'total_price' => $cart->total_price,
        ]);

        $this->assertDatabaseHas('sales_order_line_items', [
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => 1,
            'total_price' => $product->price,
        ]);

        $this->assertDatabaseHas('media', [
            'file_name' => 'attachment.pdf',
        ]);
    }
}
