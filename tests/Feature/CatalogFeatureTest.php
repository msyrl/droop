<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class CatalogFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldShowCatalogListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/catalogs');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainProductOnCatalogListPage(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/catalogs');

        $response->assertSee([
            $product->name,
            $product->featured_image_url,
            $product->formatted_price,
        ]);

        $response->assertSee([
            'value="' . $product->id . '"'
        ], false);
    }

    /**
     * @test
     */
    public function shouldShowCatalogDetailPage(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/catalogs/' . $product->id);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldShowProductOnCatalogShowPage(): void
    {
        /** @var Product */
        $product = Product::factory()->create();

        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/catalogs/' . $product->id);

        $response->assertSee([
            $product->name,
            $product->featured_image_url,
            $product->formatted_price,
            $product->description,
        ]);

        $response->assertSee([
            'value="' . $product->id . '"',
            'name="quantity"',
        ], false);
    }
}
