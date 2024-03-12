<?php

namespace Database\Factories;

use App\Models\Analyst;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalystFactory extends Factory
{
    protected $model = Analyst::class;

    public function definition()
    {
        return [
            'price_target_average'  => $this->faker->randomFloat(2, 10, 1000),
            'buy'                   => rand(1, 20),
            'hold'                  => rand(1, 20),
            'sell'                  => rand(1, 20),
        ];
    }
}
