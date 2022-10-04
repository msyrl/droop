<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
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
}
