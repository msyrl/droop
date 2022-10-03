<?php

namespace App\Http\Controllers;

use App\Builders\PaginatorBuilder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CatalogController extends Controller
{
    /**
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
            ])
            ->enableSortable([
                'name',
                'price',
                'created_at',
            ])
            ->build();

        return Response::view('catalog.index', [
            'products' => $products,
        ]);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Response::view('catalog.show', [
            'product' => $product,
        ]);
    }
}
