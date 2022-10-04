<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyCartStoreRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class MyCartController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cart = Cart::with(['lineItems.product'])
            ->where('user_id', $request->user()->id)
            ->first();

        return Response::view('my-cart.index', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param MyCartStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MyCartStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            /** @var Product */
            $product = Product::query()->findOrFail($request->get('product_id'));
            $quantity = intval($request->get('quantity', 1));

            /** @var Cart */
            $cart = Cart::query()->firstOrCreate([
                'user_id' => $request->user()->id,
            ]);

            $cart->addLineItem($product, $quantity);

            return $cart;
        });

        return Redirect::back()
            ->with('success', __('crud.added', [
                'resource' => 'item',
            ]));
    }
}
