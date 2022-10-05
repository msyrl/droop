<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyCartCheckoutStoreRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MyCartCheckoutController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var Cart */
        $cart = Cart::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return Response::view('my-cart-checkout.index', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param MyCartCheckoutStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MyCartCheckoutStoreRequest $request)
    {
        /** @var SalesOrder */
        $salesOrder = DB::transaction(function () use ($request) {
            /** @var Cart */
            $cart = Cart::query()
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            /** @var SalesOrder */
            $salesOrder = $cart->checkout();

            foreach ($request->file('attachments') as $attachment) {
                $salesOrder->addAttachment($attachment);
            }

            return $salesOrder;
        });

        return Response::redirectTo('/my/purchases/' . $salesOrder->id)
            ->with('success', __('crud.created', [
                'resource' => 'purchase',
            ]));
    }
}
