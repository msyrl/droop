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
}
