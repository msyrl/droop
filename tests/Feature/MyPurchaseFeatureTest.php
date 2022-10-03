<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderLineItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class MyPurchaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldShowMyPurchaseListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/my/purchases');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function shouldContainUserSignedInSalesOrderOnMyPurchaseListPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();

        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for($user)
            ->has(SalesOrderLineItem::factory(), 'lineItems')
            ->create();

        $response = $this->actingAs($user)->get('/my/purchases');

        $response->assertSee([
            $salesOrder->name,
            $salesOrder->formatted_status,
            $salesOrder->formatted_paid,
            $salesOrder->formatted_quantity,
            $salesOrder->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldNotContainOtherUserSalesOrderOnMyPurchaseListPage(): void
    {
        /** @var SalesOrder */
        $salesOrder = SalesOrder::factory()
            ->for(User::factory())
            ->has(SalesOrderLineItem::factory(), 'lineItems')
            ->create();

        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/my/purchases');

        $response->assertDontSee([
            $salesOrder->name,
            $salesOrder->formatted_status,
            $salesOrder->formatted_paid,
            $salesOrder->formatted_quantity,
            $salesOrder->formatted_total_price,
        ]);
    }
}
