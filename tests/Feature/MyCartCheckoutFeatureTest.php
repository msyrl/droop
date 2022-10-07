<?php

namespace Tests\Feature;

use App\Enums\SalesOrderStatusEnum;
use App\Models\Cart;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\User;
use App\Notifications\PurchaseCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class MyCartCheckoutFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        Notification::fake();
    }


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
            'total_line_items_price' => $product->price,
            'total_additional_charges_price' => SalesOrder::getDefaultAdditionalCharge(),
            'total_price' => $cart->total_price + SalesOrder::getDefaultAdditionalCharge(),
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

    /**
     * @test
     */
    public function shouldSuccessCheckoutCartAndCalculateTotalAdditionalChargesPriceBasedOnTotalAttachments(): void
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
                UploadedFile::fake()->create('attachment1.pdf'),
                UploadedFile::fake()->create('attachment2.pdf'),
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
            'total_line_items_price' => $product->price,
            'total_additional_charges_price' => SalesOrder::getDefaultAdditionalCharge() * 2,
            'total_price' => $cart->total_price + SalesOrder::getDefaultAdditionalCharge() * 2,
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
            'file_name' => 'attachment1.pdf',
        ]);

        $this->assertDatabaseHas('media', [
            'file_name' => 'attachment2.pdf',
        ]);
    }

    /**
     * @test
     */
    public function shouldSendNotificationWhenCheckoutSuccess(): void
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

        $this->actingAs($user)->post('/my/cart/checkout', [
            'attachments' => [
                UploadedFile::fake()->create('attachment.pdf'),
            ],
        ]);

        Notification::assertSentTo($user, PurchaseCreated::class);
    }
}
