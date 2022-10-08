<?php

namespace App\Imports;

use App\Models\Product;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ProductsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        /** @var Product */
        $product = Product::create([
            'name' => $row['name'],
            'sku' => $row['sku'],
            'price' => $row['price'],
            'description' => $row['description'],
        ]);

        if ($row['featured_image'] && $row['featured_image'] != '') {
            $product->setFeaturedImageFromUrl(
                $this->covertToGoogleDriveUrl(
                    $row['featured_image']
                )
            );
        }

        if ($row['images'] && $row['images'] != '') {
            foreach (explode(';', $row['images']) as $url) {
                $product->setImageFromUrl(
                    $this->covertToGoogleDriveUrl(
                        $url
                    )
                );
            }
        }
    }

    protected function covertToGoogleDriveUrl(string $url): string
    {
        $fileId = preg_replace('/(.*)d\/|\/view(.*)/', '', $url);

        return "https://drive.google.com/uc?id={$fileId}";
    }
}
