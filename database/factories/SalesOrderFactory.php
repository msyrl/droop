<?php

namespace Database\Factories;

use App\Enums\SalesOrderStatusEnum;
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
}
