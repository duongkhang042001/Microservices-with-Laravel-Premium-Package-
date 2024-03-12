<?php

namespace App\Repositories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\IncomeStatement;
use StockPickr\Common\Formatters\Formatter;

class IncomeStatementRepository extends FinancialStatementRepository
{
    public function getAttributes(): array
    {
        return [
            'totalRevenue' => [
                'name' => 'Total Revenue',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'costOfRevenue' => [
                'name' => 'Cost of Revenue',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => false,
                'isInverted' => true,
            ],
            'grossProfit' => [
                'name' => 'Gross Profit',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'operatingIncome' => [
                'name' => 'Operating Income',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'pretaxIncome' => [
                'name' => 'Pretax Income',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'incomeTax' => [
                'name' => 'Income Tax',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => true,
            ],
            'interestExpense' => [
                'name' => 'Net Interest Expense / Income',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'researchAndDevelopment' => [
                'name' => 'Research and Development',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => true,
            ],
            'sellingGeneralAdministrative' => [
                'name' => 'Selling, General and Administrative',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => true,
            ],
            'netIncome' => [
                'name' => 'Net Income',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'ebit' => [
                'name' => 'EBIT',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'eps' => [
                'name' => 'EPS',
                'formatter' => Formatter::MONEY,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
        ];
    }

    /**
     * ['total_revenue' => 100, 'net_income' => 10 ...]
     */
    public function save(Company $company, int $year, array $data): IncomeStatement
    {
        return IncomeStatement::create([
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => $year,
            'total_revenue' => $data['total_revenue'],
            'cost_of_revenue' => $data['cost_of_revenue'],
            'gross_profit' => $data['gross_profit'],
            'operating_income' => $data['operating_income'],
            'pretax_income' => $data['pretax_income'],
            'income_tax' => $data['income_tax'],
            'interest_expense' => $data['interest_expense'],
            'research_and_development' => $data['research_and_development'],
            'sga' => $data['sga'],
            'net_income' => $data['net_income'],
            'ebit' => $data['ebit'],
            'eps' => $data['eps'],
        ]);
    }

    public function findYearsWhereHasData(Company $company): array
    {
        return IncomeStatement::select('year')
            ->where('company_id', $company->id)
            ->whereNotNull('year')
            ->groupBy('year')
            ->get('year')
            ->pluck('year')
            ->all();
    }
}
