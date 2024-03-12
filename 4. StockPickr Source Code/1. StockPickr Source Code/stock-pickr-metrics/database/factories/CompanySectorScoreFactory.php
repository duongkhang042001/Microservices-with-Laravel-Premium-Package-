<?php

namespace Database\Factories;

use App\Enums\Scores;
use App\Models\Company;
use App\Models\CompanySectorScore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanySectorScoreFactory extends Factory
{
    protected $model = CompanySectorScore::class;

    public function definition()
    {
        return [
            'ticker' => fn () => substr(Str::upper($this->faker->unique()->word), 0,  3) . $this->faker->numberBetween(1, 999),
            'company_id' => function () {
                return Company::factory()->create();
            },
            'debt_to_capital' => rand(Scores::D, Scores::A),
            'current_ratio' => rand(Scores::D, Scores::A),
            'quick_ratio' => rand(Scores::D, Scores::A),
            'cash_to_debt' => rand(Scores::D, Scores::A),
            'interest_to_operating_profit' => rand(Scores::D, Scores::A),
            'long_term_debt_to_ebitda' => rand(Scores::D, Scores::A),
            'interest_coverage_ratio' => rand(Scores::D, Scores::A),
            'debt_to_assets' => rand(Scores::D, Scores::A),
            'operating_cash_flow_to_current_liabilities' => rand(Scores::D, Scores::A),
            'capex_as_percent_of_revenue' => rand(Scores::D, Scores::A),
            'capex_as_percent_of_operating_cash_flow' => rand(Scores::D, Scores::A),
            'payout_ratio' => rand(Scores::D, Scores::A),
            'roic' => rand(Scores::D, Scores::A),
            'croic' => rand(Scores::D, Scores::A),
            'rota' => rand(Scores::D, Scores::A),
            'roa' => rand(Scores::D, Scores::A),
            'roe' => rand(Scores::D, Scores::A),
            'free_cash_flow_to_revenue' => rand(Scores::D, Scores::A),
            'net_margin' => rand(Scores::D, Scores::A),
            'operating_margin' => rand(Scores::D, Scores::A),
            'gross_margin' => rand(Scores::D, Scores::A),
            'operating_cash_flow_margin' => rand(Scores::D, Scores::A),
            'sga_to_gross_profit' => rand(Scores::D, Scores::A),
            'eps_growth' => rand(Scores::D, Scores::A),
            'net_income_growth' => rand(Scores::D, Scores::A),
            'total_revenue_growth' => rand(Scores::D, Scores::A),
        ];
    }
}
