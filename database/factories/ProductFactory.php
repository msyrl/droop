<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Product #' . $this->faker->randomNumber(3, true),
            'sku' => $this->faker->randomNumber(6, true),
            'price' => $this->faker->randomNumber(5, true),
            'description' => $this->faker->text(),
        ];
    }
}
