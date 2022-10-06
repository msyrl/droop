<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderInvoiceController extends Controller
{
    /**
     * @param SalesOrder $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function index(SalesOrder $salesOrder)
    {
        $salesOrder->load(['lineItems']);

        return Pdf::loadView('sales-order.invoice.index', [
            'salesOrder' => $salesOrder,
        ])->stream("invoice-{$salesOrder->name}.pdf");
    }
}
