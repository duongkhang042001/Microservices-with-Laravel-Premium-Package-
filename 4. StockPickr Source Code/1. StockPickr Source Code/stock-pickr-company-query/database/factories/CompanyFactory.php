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
            'ticker' => Str::substr($this->faker->uuid, 0, 6),
            'name' => $this->faker->words(3, true),
            'sector' => $this->faker->word,
        ];
    }
}
