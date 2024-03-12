<?php

namespace Database\Factories;

use App\Models\CompanyUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyUpdateFactory extends Factory
{
    protected $model = CompanyUpdate::class;

    public function definition()
    {
        return [
            'ticker'                    => Str::upper(Str::random(3)) . rand(100, 999),
            'market_data_updated_at'    => now(),
            'financials_updated_at'     => now()
        ];
    }
}
