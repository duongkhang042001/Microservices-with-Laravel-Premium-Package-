<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'ticker' => substr($this->faker->uuid, 0,  6),
            'sector' => $this->faker->word(),
            'total_scores' => rand(1, 50),
            'total_score_percent' => $this->faker->randomFloat(4, 0, 1),
        ];
    }
}
