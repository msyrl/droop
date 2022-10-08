<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImportStoreRequest;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    /**
     * @param ProductImportStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductImportStoreRequest $request)
    {
        Excel::import(
            new ProductsImport,
            $request->file('file')
        );

        return Response::redirectTo('/products')
            ->with('success', __('crud.created', [
                'resource' => __('product'),
            ]));
    }
}
