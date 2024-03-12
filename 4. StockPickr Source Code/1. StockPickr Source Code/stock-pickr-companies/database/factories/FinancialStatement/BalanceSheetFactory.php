<?php

namespace Database\Factories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\BalanceSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalanceSheetFactory extends Factory
{
    protected $model = BalanceSheet::class;

    public function definition()
    {
        $company = Company::factory()->create();
        return [
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => 2020,
            'cash' => 10,
            'current_cash' => 10,
            'total_current_assets' => 10,
            'net_intangible_assets' => 10,
            'tangible_assets' => 10,
            'short_term_investments' => 10,
            'tradeaccounts_receivable_current' => 10,
            'total_assets' => 10,
            'total_equity' => 10,
            'current_portion_of_long_term_debt' => 10,
            'total_current_liabilities' => 10,
            'long_term_debt' => 10,
            'total_liabalities' => 10,
        ];
    }
}
