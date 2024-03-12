<?php

namespace Database\Factories;

use App\Models\DeniedCompany;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DeniedCompanyFactory extends Factory
{
    protected $model = DeniedCompany::class;

    public function definition()
    {
        return [
            'ticker' => substr(Str::upper($this->faker->unique()->word), 0,  3) . $this->faker->numberBetween(100, 999),
            'reason' => $this->faker->words(5, true)
        ];
    }
}
