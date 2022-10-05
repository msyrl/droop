<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderLineItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class SalesOrderFeatureTest extends TestCase
{
    use RefreshDatabase;

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
            $salesOrder->formatted_status,
            $salesOrder->formatted_paid,
            $salesOrder->formatted_quantity,
            $salesOrder->formatted_total_line_items_price,
            $salesOrder->formatted_total_additional_price,
            $salesOrder->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldContainSalesOrdersOnSalesOrderShowPage(): void
    {
        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for(User::factory())
            ->has(SalesOrderLineItem::factory(), 'lineItems')
            ->create();

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_sales_orders())
            ->build();
        $response = $this->actingAs($user)->get('/sales-orders/' . $salesOrder->id);

        $response->assertSee([
            $salesOrder->name,
            $salesOrder->formatted_status,
            $salesOrder->formatted_paid,
            $salesOrder->formatted_quantity,
            $salesOrder->formatted_total_line_items_price,
            $salesOrder->formatted_total_additional_price,
            $salesOrder->formatted_total_price,
            $salesOrder->user->name,
            $salesOrder->user->email,
            $salesOrder->lineItems[0]->name,
            $salesOrder->lineItems[0]->formatted_price,
            $salesOrder->lineItems[0]->formatted_quantity,
            $salesOrder->lineItems[0]->formatted_total_price,
        ]);
    }
}
