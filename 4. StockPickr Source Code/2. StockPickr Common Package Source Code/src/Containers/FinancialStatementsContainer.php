<?php

namespace StockPickr\Common\Containers;

use StockPickr\Common\Containers\Company\FinancialStatement\BalanceSheet\BalanceSheetContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\CashFlow\CashFlowContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement\IncomeStatementContainer;

final class FinancialStatementsContainer extends Container
{
    public IncomeStatementContainer $incomeStatements;
    public BalanceSheetContainer $balanceSheets;
    public CashFlowContainer $cashFlows;

    public static function create(
        IncomeStatementContainer $incomeStatements, 
        BalanceSheetContainer $balanceSheets, 
        CashFlowContainer $cashFlows
    ): static {
        return static::from(compact('incomeStatements', 'balanceSheets', 'cashFlows'));
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->incomeStatements = $data['incomeStatements'];
        $container->balanceSheets = $data['balanceSheets'];
        $container->cashFlows = $data['cashFlows'];

        return $container;
    }
}