<?php

namespace App\Http\Controllers;

use App\Builders\PaginatorBuilder;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = PaginatorBuilder::make(
            $request,
            Product::query()
        )
            ->enableSearchable([
                'name',
                'sku',
                'price',
            ])
            ->enableSortable([
                'name',
                'sku',
                'price',
                'created_at',
            ])
            ->build();

        return Response::view('product.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        /** @var Product */
        $product = Product::create($request->validated());

        return Response::redirectTo('/products/' . $product->id)
            ->with('success', __('crud.created', [
                'resource' => 'product',
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Response::view('product.show', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());

        return Response::redirectTo('/products/' . $product->id)
            ->with('success', __('crud.updated', [
                'resource' => 'product',
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
