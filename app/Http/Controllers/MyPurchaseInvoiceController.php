<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MyPurchaseInvoiceController extends Controller
{
    /**
     * @param string $purchaseId
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(string $purchaseId, Request $request)
    {
        /** @var SalesOrder */
        $purchase = SalesOrder::with(['lineItems'])
            ->where('user_id', $request->user()->id)
            ->where('id', $purchaseId)
            ->firstOrFail();

        return Pdf::loadView('my-purchase.invoice.index', [
            'purchase' => $purchase,
        ])->stream("invoice-{$purchase->name}.pdf");
    }
}
