<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement\CashFlow;

use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;

final class CashFlowContainer extends FinancialStatementContainer
{
    protected array $items = [
        'netIncome',
        'operatingCashFlow',
        'capex',
        'cashDividendPaid',
        'deprecationAmortization',
        'freeCashFlow',
        'cashFromFinancing',
        'cashFromInvesting',
    ];

    public FinancialStatementItemContainer $netIncome;
    public FinancialStatementItemContainer $operatingCashFlow;
    public FinancialStatementItemContainer $capex;
    public FinancialStatementItemContainer $cashDividendPaid;
    public FinancialStatementItemContainer $deprecationAmortization;
    public FinancialStatementItemContainer $freeCashFlow;
    public FinancialStatementItemContainer $cashFromFinancing;
    public FinancialStatementItemContainer $cashFromInvesting;

    public static function from(array $data): CashFlowContainer
    {
        $container = new static();

        $container->netIncome = FinancialStatementItemContainer::from($data['netIncome']);
        $container->operatingCashFlow = FinancialStatementItemContainer::from($data['operatingCashFlow']);
        $container->capex = FinancialStatementItemContainer::from($data['capex']);
        $container->cashDividendPaid = FinancialStatementItemContainer::from($data['cashDividendPaid']);
        $container->deprecationAmortization = FinancialStatementItemContainer::from($data['deprecationAmortization']);
        $container->freeCashFlow = FinancialStatementItemContainer::from($data['freeCashFlow']);
        $container->cashFromFinancing = FinancialStatementItemContainer::from($data['cashFromFinancing']);
        $container->cashFromInvesting = FinancialStatementItemContainer::from($data['cashFromInvesting']);

        return $container;
    }
}
