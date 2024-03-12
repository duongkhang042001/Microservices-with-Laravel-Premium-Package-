<?php

namespace Database\Factories;

use App\Models\ShareData;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShareDataFactory extends Factory
{
    protected $model = ShareData::class;

    public function definition()
    {
        return [
            'price'                 => $this->faker->randomFloat(2, 10, 1000),
            'market_cap'            => $this->faker->randomFloat(2, 500, 50000),
            'shares_outstanding'    => $this->faker->randomFloat(2, 100, 100000),
            'beta'                  => $this->faker->randomFloat(2, 0.5, 5)
        ];
    }
}
