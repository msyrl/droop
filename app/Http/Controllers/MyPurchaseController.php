<?php

namespace App\Http\Controllers;

use App\Builders\PaginatorBuilder;
use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MyPurchaseController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = PaginatorBuilder::make(
            $request,
            SalesOrder::query()
                ->where('user_id', $request->user()->id)
        )
            ->enableSearchable([
                'name',
                'status',
                'quantity',
                'total_price',
            ])
            ->enableSortable([
                'name',
                'quantity',
                'total_price',
            ])
            ->build();

        return Response::view('my-purchase.index', [
            'purchases' => $purchases,
        ]);
    }

    /**
     * @param string $purchaseId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(string $purchaseId, Request $request)
    {
        /** @var SalesOrder */
        $purchase = SalesOrder::with(['user', 'lineItems'])
            ->where('user_id', $request->user()->id)
            ->where('id', $purchaseId)
            ->firstOrFail();

        return Response::view('my-purchase.show', [
            'purchase' => $purchase,
        ]);
    }
}
