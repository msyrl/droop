<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Enums\SalesOrderStatusEnum;
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
            $salesOrder->formatted_total_additional_charges_price,
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
            $salesOrder->formatted_total_additional_charges_price,
            $salesOrder->formatted_total_price,
            $salesOrder->user->name,
            $salesOrder->user->email,
            $salesOrder->lineItems[0]->name,
            $salesOrder->lineItems[0]->formatted_price,
            $salesOrder->lineItems[0]->formatted_quantity,
            $salesOrder->lineItems[0]->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldSuccessUpdateSalesOrder(): void
    {
        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for(User::factory())
            ->has(SalesOrderLineItem::factory(), 'lineItems')
            ->create([
                'status' => SalesOrderStatusEnum::waiting(),
                'paid' => false,
            ]);

        /** @var User */
        $user = UserBuilder::make()
            ->addPermission(PermissionEnum::view_sales_orders())
            ->build();
        $response = $this->actingAs($user)->put('/sales-orders/' . $salesOrder->id, [
            'total_additional_charges_price' => 1000,
            'status' => SalesOrderStatusEnum::processing(),
            'paid' => true,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('sales_orders', [
            'id' => $salesOrder->id,
            'status' => SalesOrderStatusEnum::processing(),
            'paid' => true,
            'total_additional_charges_price' => 1000,
            'total_price' => $salesOrder->total_price + 1000,
        ]);
    }

    /**
     * @test
     */
    public function shouldShowSalesOrderInvoicePageAsPdf()
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
        $response = $this->actingAs($user)->get('/sales-orders/' . $salesOrder->id . '/invoice');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
