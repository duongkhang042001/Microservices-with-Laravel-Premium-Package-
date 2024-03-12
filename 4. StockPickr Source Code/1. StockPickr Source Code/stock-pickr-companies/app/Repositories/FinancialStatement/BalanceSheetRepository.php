<?php

namespace App\Repositories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\BalanceSheet;
use StockPickr\Common\Formatters\Formatter;

class BalanceSheetRepository extends FinancialStatementRepository
{
    public function getAttributes(): array
    {
        return [
            'cash' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Cash & Short Term Investments',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'currentCash' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Cash',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'totalCurrentAssets' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Total Current Assets',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'netIntangibleAssets' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Net Intangible Assets',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'tangibleAssets' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Tangible Assets',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'shortTermInvestments' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Short Term Investments',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'currentAccountAndReceivables' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Accounts and Receivables',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'totalAssets' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Total Assets',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'totalEquity' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Total Equity',
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'currentPortionOfLongTermDebt' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Current Portion of Long Term Debt',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'totalCurrentLiabilities' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Total Current Liabilities',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'longTermDebt' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Long Term Debt',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'totalLiabilities' => [
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'name' => 'Total Liabilities',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
        ];
    }

    public function save(Company $company, int $year, array $data): BalanceSheet
    {
        return BalanceSheet::create([
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => $year,
            'cash' => $data['cash'],
            'current_cash' => $data['current_cash'],
            'total_current_assets' => $data['total_current_assets'],
            'net_intangible_assets' => $data['net_intangible_assets'],
            'tangible_assets' => $data['tangible_assets'],
            'short_term_investments' => $data['short_term_investments'],
            'tradeaccounts_receivable_current' => $data['tradeaccounts_receivable_current'],
            'total_assets' => $data['total_assets'],
            'total_equity' => $data['total_equity'],
            'current_portion_of_long_term_debt' => $data['current_portion_of_long_term_debt'],
            'total_current_liabilities' => $data['total_current_liabilities'],
            'long_term_debt' => $data['long_term_debt'],
            'total_liabalities' => $data['total_liabalities'],
        ]);
    }
}
