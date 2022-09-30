<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Permission;
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
}
