<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'ticker' => Str::substr($this->faker->uuid, 0, 6),
            'financial_statements' => [
                'incomeStatements' => [
                    'totalRevenue' => [
                        'data' => [
                            2020 => $this->faker->randomFloat(4, 100, 1000),
                            2019 => $this->faker->randomFloat(4, 100, 750),
                        ]
                    ]
                ],

                'balanceSheets' => [
                    'totalEquity' => [
                        'data' => [
                            2020 => $this->faker->randomFloat(4, 100, 500),
                            2019 => $this->faker->randomFloat(4, 100, 330),
                        ]
                    ]
                ],

                'cashFlows' => [
                    'operatingCashFlow' => [
                        'data' => [
                            2020 => $this->faker->randomFloat(4, -10, 220),
                            2019 => $this->faker->randomFloat(4, -20, 150),
                        ]
                    ]
                ]
            ]
        ];
    }
}
