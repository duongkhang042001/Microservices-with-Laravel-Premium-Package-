<?php

namespace Database\Factories;

use App\Models\Chart;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChartFactory extends Factory
{
    protected $model = Chart::class;

    public function definition()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        return [
            'ticker'        => $company->ticker,
            'company_id'    => $company->id,
            'chart'         => 'revenue',
            'years'         => [2020, 2019, 2018, 2017],
            'data'          => []
        ];
    }
}
