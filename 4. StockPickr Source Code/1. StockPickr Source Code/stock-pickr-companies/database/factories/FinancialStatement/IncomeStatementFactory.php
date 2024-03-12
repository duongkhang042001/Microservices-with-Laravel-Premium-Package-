<?php

namespace Database\Factories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\IncomeStatement;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeStatementFactory extends Factory
{
    protected $model = IncomeStatement::class;

    public function definition()
    {
        $company = Company::factory()->create();
        return [
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => 2020,
            'total_revenue' => 10,
            'cost_of_revenue' => 10,
            'gross_profit' => 10,
            'operating_income' => 10,
            'pretax_income' => 10,
            'income_tax' => 10,
            'interest_expense' => 10,
            'research_and_development' => 10,
            'sga' => 10,
            'net_income' => 100,
            'ebit' => 10,
            'eps' => 10,
        ];
    }
}
