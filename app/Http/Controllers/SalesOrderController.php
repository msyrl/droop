<?php

namespace App\Http\Controllers;

use App\Builders\PaginatorBuilder;
use App\Http\Requests\SalesOrderUpdateRequest;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $salesOrders = PaginatorBuilder::make(
            $request,
            SalesOrder::query()
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

        return Response::view('sales-order.index', [
            'salesOrders' => $salesOrders,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\SalesOrder $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['user', 'lineItems.product']);

        return Response::view('sales-order.show', [
            'salesOrder' => $salesOrder,
        ]);
    }

    /**
     * @param SalesOrderUpdateRequest $request
     * @param SalesOrder $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function update(SalesOrderUpdateRequest $request, SalesOrder $salesOrder)
    {
        $salesOrder->update(
            $request->validated() + [
                'total_price' => $salesOrder->total_line_items_price + $request->get('total_additional_charges_price'),
            ],
        );

        return Response::redirectTo('/sales-orders/' . $salesOrder->id)
            ->with('success', __('crud.updated', [
                'resource' => 'sales order',
            ]));
    }
}
