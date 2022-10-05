<?php

namespace Database\Factories;

use App\Enums\SalesOrderStatusEnum;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(SalesOrderStatusEnum::toValues()),
            'paid' => $this->faker->boolean(),
            'name' => '#' . $this->faker->randomNumber(6, true),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (SalesOrder $salesOrder) {
            $salesOrder->quantity = $salesOrder->lineItems()->sum('quantity');
            $salesOrder->total_line_items_price = $salesOrder->lineItems()->sum('total_price');
            $salesOrder->total_price = $salesOrder->total_line_items_price + $salesOrder->total_additional_price;
            $salesOrder->save();
        });
    }
}
