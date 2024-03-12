<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyMetric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyMetricFactory extends Factory
{
    protected $model = CompanyMetric::class;

    public function definition()
    {
        $ticker = substr(Str::upper($this->faker->word), 0,  3) . $this->faker->numberBetween(1, 999);
        return [
            'ticker' => fn () => $ticker,
            'company_id' => fn () => Company::factory(['ticker' => $ticker])->create(),
            'year' => rand(2016, 2020),
            'debt_to_capital' => $this->faker->randomFloat(4, 0, 1),
            'current_ratio' => $this->faker->randomFloat(4, 0, 1),
            'quick_ratio' => $this->faker->randomFloat(4, 0, 1),
            'cash_to_debt' => $this->faker->randomFloat(4, 0, 1),
            'interest_to_operating_profit' => $this->faker->randomFloat(4, 0, 1),
            'long_term_debt_to_ebitda' => $this->faker->randomFloat(4, 0, 1),
            'interest_coverage_ratio' => $this->faker->randomFloat(4, 0, 1),
            'debt_to_assets' => $this->faker->randomFloat(4, 0, 1),
            'operating_cash_flow_to_current_liabilities' => $this->faker->randomFloat(4, 0, 1),
            'capex_as_percent_of_revenue' => $this->faker->randomFloat(4, 0, 1),
            'capex_as_percent_of_operating_cash_flow' => $this->faker->randomFloat(4, 0, 1),
            'payout_ratio' => $this->faker->randomFloat(4, 0, 1),
            'roic' => $this->faker->randomFloat(4, 0, 1),
            'croic' => $this->faker->randomFloat(4, 0, 1),
            'rota' => $this->faker->randomFloat(4, 0, 1),
            'roa' => $this->faker->randomFloat(4, 0, 1),
            'roe' => $this->faker->randomFloat(4, 0, 1),
            'free_cash_flow_to_revenue' => $this->faker->randomFloat(4, 0, 1),
            'net_margin' => $this->faker->randomFloat(4, 0, 1),
            'operating_margin' => $this->faker->randomFloat(4, 0, 1),
            'gross_margin' => $this->faker->randomFloat(4, 0, 1),
            'operating_cash_flow_margin' => $this->faker->randomFloat(4, 0, 1),
            'sga_to_gross_profit' => $this->faker->randomFloat(4, 0, 1),
            'eps_growth' => $this->faker->randomFloat(4, 0, 1),
            'net_income_growth' => $this->faker->randomFloat(4, 0, 1),
            'total_revenue_growth' => $this->faker->randomFloat(4, 0, 1),
        ];
    }
}
