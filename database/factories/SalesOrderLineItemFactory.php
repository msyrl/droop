<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesOrderLineItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $quantity = $this->faker->randomNumber(2);

        return [
            'product_id' => Product::factory(),
            'name' => function (array $attributes) {
                return Product::find($attributes['product_id'])->name;
            },
            'sku' => function (array $attributes) {
                return Product::find($attributes['product_id'])->sku;
            },
            'price' => function (array $attributes) {
                return Product::find($attributes['product_id'])->price;
            },
            'quantity' => $quantity,
            'total_price' => function (array $attributes) use ($quantity) {
                return Product::find($attributes['product_id'])->price * $quantity;
            },
        ];
    }
}
