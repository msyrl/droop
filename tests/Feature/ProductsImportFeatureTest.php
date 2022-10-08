<?php

namespace Tests\Feature;

use App\Imports\ProductsImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ProductsImportFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldSuccessImportProductsFromCsv(): void
    {
        $filePath = public_path('/sample/products_sample.csv');

        Excel::import(new ProductsImport(), $filePath);

        $this->assertDatabaseHas('products', [
            'name' => 'Product Import #1',
            'sku' => '0001',
            'price' => 10000,
        ]);

        $this->assertDatabaseCount('media', 6);
    }
}
