<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Notifications\PurchaseCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SalesOrderNotificationController extends Controller
{
    /**
     * @param SalesOrder $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function send(SalesOrder $salesOrder)
    {
        $salesOrder->load(['lineItems']);
        $salesOrder->user->notify(new PurchaseCreated($salesOrder));

        return Response::redirectTo('/sales-orders/' . $salesOrder->id)
            ->with('success', __('Notification sent'));
    }
}
