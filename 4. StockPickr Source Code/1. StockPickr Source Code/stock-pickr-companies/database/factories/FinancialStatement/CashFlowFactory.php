<?php

namespace Database\Factories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\CashFlow;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashFlowFactory extends Factory
{
    protected $model = CashFlow::class;

    public function definition()
    {
        $company = Company::factory()->create();
        return [
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => 2020,
            'net_income' => 10,
            'operating_cash_flow' => 10,
            'capex' => 10,
            'cash_dividends_paid' => 10,
            'depreciation_amortization' => 10,
            'free_cash_flow' => 10,
            'cash_from_financing' => 10,
            'cash_from_investing' => 10,
        ];
    }
}
