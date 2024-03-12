<?php

namespace App\Repositories\FinancialStatement;

use App\Models\Company\Company;
use App\Models\FinancialStatement\CashFlow;
use StockPickr\Common\Formatters\Formatter;

class CashFlowRepository extends FinancialStatementRepository
{
    public function getAttributes(): array
    {
        return [
            'netIncome' => [
                'name' => 'Net Income',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'operatingCashFlow' => [
                'name' => 'Operating Cash Flow',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'capex' => [
                'name' => 'CAPEX',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'cashDividendPaid' => [
                'name' => 'Cash Dividends Paid',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'deprecationAmortization' => [
                'name' => 'Deprecation, amortization',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
            ],
            'freeCashFlow' => [
                'name' => 'Free Cash Flow',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'cashFromFinancing' => [
                'name' => 'Cash from Financing',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
            'cashFromInvesting' => [
                'name' => 'Cash from Investing',
                'formatter' => Formatter::FINANCIAL_NUMBER,
                'shouldHighlightNegativeValue' => true,
                'isInverted' => false,
            ],
        ];
    }

    public function save(Company $company, int $year, array $data): CashFlow
    {
        return CashFlow::create([
            'ticker' => $company->ticker,
            'company_id' => $company->id,
            'year' => $year,
            'net_income' => $data['net_income'],
            'operating_cash_flow' => $data['operating_cash_flow'],
            'capex' => $data['capex'],
            'cash_dividends_paid' => $data['cash_dividends_paid'],
            'depreciation_amortization' => $data['depreciation_amortization'],
            'free_cash_flow' => $data['free_cash_flow'],
            'cash_from_financing' => $data['cash_from_financing'],
            'cash_from_investing' => $data['cash_from_investing'],
        ]);
    }
}
