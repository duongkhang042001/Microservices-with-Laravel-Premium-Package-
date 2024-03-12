<?php

namespace App\Repositories\FinancialStatement\Factory;

use App\Repositories\FinancialStatement\BalanceSheetRepository;
use App\Repositories\FinancialStatement\CashFlowRepository;
use App\Repositories\FinancialStatement\FinancialStatementRepository;
use App\Repositories\FinancialStatement\IncomeStatementRepository;

class FinancialStatementRepositoryFactory
{
    /**
     * @param string $table
     * @return FinancialStatementRepository
     */
    public function create(string $table): FinancialStatementRepository
    {
        switch ($table) {
            case 'income_statements':
                return resolve(IncomeStatementRepository::class);
            case 'balance_sheets':
                return resolve(BalanceSheetRepository::class);
            case 'cash_flows':
                return resolve(CashFlowRepository::class);
            default:
                throw new \InvalidArgumentException('Financial statement repository not found for: ' . $table);
        }
    }
}
