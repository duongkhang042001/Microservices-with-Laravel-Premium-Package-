<?php

namespace App\Models\FinancialStatement\Factory;

use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\FinancialStatement;
use App\Models\FinancialStatement\IncomeStatement;
use App\Services\FinancialStatement\FinancialStatementService;

class FinancialStatementFactory
{
    public function create(string $type): FinancialStatement
    {
        switch ($type) {
            case FinancialStatementService::TYPE_BALANCE_SHEET:
                return new BalanceSheet();
            case FinancialStatementService::TYPE_INCOME_STATEMENT:
                return new IncomeStatement();
            case FinancialStatementService::TYPE_CASH_FLOW:
                return new CashFlow();
            default:
                throw new \InvalidArgumentException('No financial statement for type: ' . $type);
        }
    }
}
