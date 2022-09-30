<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @test
     */
    public function shouldShowProductListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_products())
            ->build();
        $response = $this->actingAs($user)->get('/products');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainProductsOnProductListPage(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_products())
            ->build();
        $response = $this->actingAs($user)->get('/products');

        $response->assertSee([
            $product->name,
            $product->sku,
            $product->formatted_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldShowProductCreatePage(): void
    {
        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::manage_products())
            ->build();
        $response = $this->actingAs($user)->get('/products/create');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainProductInputOnProductCreatePage(): void
    {
        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::manage_products())
            ->build();
        $response = $this->actingAs($user)->get('/products/create');

        $response->assertSee([
            'name="name"',
            'name="sku"',
            'name="price"',
            'name="description"',
        ], false);
    }

    /**
     * @test
     */
    public function shouldSuccessCreateProduct(): void
    {
        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::manage_products())
            ->build();
        $response = $this->actingAs($user)->post('/products', [
            'name' => 'Product #1',
            'sku' => 'SKU123',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet',
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Product #1',
            'sku' => 'SKU123',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
    }

    /**
     * @test
     */
    public function shouldContainProductInputOnProductShowPage(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::manage_products())
            ->build();
        $response = $this->actingAs($user)->get('/products/' . $product->id);

        $response->assertSee([
            'value="PUT"',
            'name="name"',
            'name="sku"',
            'name="price"',
            'name="description"',
        ], false);

        $response->assertSee([
            $product->name,
            $product->sky,
            $product->price,
            $product->description,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessUpdateProduct(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::manage_products())
            ->build();
        $response = $this->actingAs($user)->put('/products/' . $product->id, [
            'name' => 'Product #1',
            'sku' => 'SKU123',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet',
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Product #1',
            'sku' => 'SKU123',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet',
        ]);
    }
}
