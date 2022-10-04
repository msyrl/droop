<?php

namespace Tests\Feature;

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
        $purchase = SalesOrder::factory()
            ->for($user)
            ->has(SalesOrderLineItem::factory(2), 'lineItems')
            ->create();

        $response = $this->actingAs($user)->get('/my/purchases');

        $response->assertSee([
            $purchase->name,
            $purchase->formatted_status,
            $purchase->formatted_paid,
            $purchase->formatted_quantity,
            $purchase->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldNotContainOtherUserSalesOrderOnMyPurchaseListPage(): void
    {
        /** @var SalesOrder */
        $purchase = SalesOrder::factory()
            ->for(User::factory())
            ->has(SalesOrderLineItem::factory(2), 'lineItems')
            ->create();

        /** @var User */
        $user = UserBuilder::make()->build();
        $response = $this->actingAs($user)->get('/my/purchases');

        $response->assertDontSee([
            $purchase->name,
            $purchase->formatted_status,
            $purchase->formatted_paid,
            $purchase->formatted_quantity,
            $purchase->formatted_total_price,
        ]);
    }

    /**
     * @test
     */
    public function shouldShowPurchaseOnPurchaseShowPage(): void
    {
        /** @var User */
        $user = UserBuilder::make()->build();

        /** @var SalesOrder */
        $purchase = SalesOrder::factory()
            ->for($user)
            ->has(SalesOrderLineItem::factory(2), 'lineItems')
            ->create();

        $response = $this->actingAs($user)->get('/my/purchases/' . $purchase->id);

        $response->assertSee([
            $purchase->name,
            $purchase->formatted_status,
            $purchase->formatted_paid,
            $purchase->formatted_quantity,
            $purchase->formatted_total_price,
            $purchase->user->name,
            $purchase->user->email,
            $purchase->lineItems[0]->name,
            $purchase->lineItems[0]->formatted_price,
            $purchase->lineItems[0]->formatted_quantity,
            $purchase->lineItems[0]->formatted_total_price,
        ]);
    }
}
