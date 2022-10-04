<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Cart $cart) {
            $cart->quantity = $cart->lineItems()->sum('quantity');
            $cart->total_price = $cart->lineItems()->sum('total_price');
            $cart->save();
        });
    }
}
