<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class SalesOrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @test
     */
    public function shouldShowSalesOrderListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_sales_orders())
            ->build();
        $response = $this->actingAs($user)->get('/sales-orders');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainSalesOrdersOnSalesOrderListPage(): void
    {
        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for(User::factory())
            ->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_sales_orders())
            ->build();
        $response = $this->actingAs($user)->get('/sales-orders');

        $response->assertSee([
            $salesOrder->name,
            $salesOrder->status,
            $salesOrder->quantity,
            $salesOrder->total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldContainSalesOrdersOnSalesOrderShowPage(): void
    {
        /** @var User */
        $salesOrderUser = User::factory();

        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for($salesOrderUser)
            ->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_sales_orders())
            ->build();
        $response = $this->actingAs($user)->get('/sales-orders/' . $salesOrder->id);

        $response->assertSee([
            $salesOrder->name,
            $salesOrder->status,
            $salesOrder->quantity,
            $salesOrder->total_price,
            $salesOrderUser->name,
            $salesOrderUser->email,
        ]);
    }
}
