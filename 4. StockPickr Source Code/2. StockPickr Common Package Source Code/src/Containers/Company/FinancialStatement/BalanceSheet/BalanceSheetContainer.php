<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement\BalanceSheet;

use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;

final class BalanceSheetContainer extends FinancialStatementContainer
{
    protected array $items = [
        'cash',
        'currentCash',
        'totalCurrentAssets',
        'netIntangibleAssets',
        'tangibleAssets',
        'shortTermInvestments',
        'currentAccountAndReceivables',
        'totalAssets',
        'totalEquity',
        'currentPortionOfLongTermDebt',
        'totalCurrentLiabilities',
        'longTermDebt',
        'totalLiabilities',
    ];

    public FinancialStatementItemContainer $cash;
    public FinancialStatementItemContainer $currentCash;
    public FinancialStatementItemContainer $totalCurrentAssets;
    public FinancialStatementItemContainer $netIntangibleAssets;
    public FinancialStatementItemContainer $tangibleAssets;
    public FinancialStatementItemContainer $shortTermInvestments;
    public FinancialStatementItemContainer $currentAccountAndReceivables;
    public FinancialStatementItemContainer $totalAssets;
    public FinancialStatementItemContainer $totalEquity;
    public FinancialStatementItemContainer $currentPortionOfLongTermDebt;
    public FinancialStatementItemContainer $totalCurrentLiabilities;
    public FinancialStatementItemContainer $longTermDebt;
    public FinancialStatementItemContainer $totalLiabilities;

    public static function from(array $data): BalanceSheetContainer
    {
        $container = new static();

        $container->cash = FinancialStatementItemContainer::from($data['cash']);
        $container->currentCash = FinancialStatementItemContainer::from($data['currentCash']);
        $container->totalCurrentAssets = FinancialStatementItemContainer::from($data['totalCurrentAssets']);
        $container->netIntangibleAssets = FinancialStatementItemContainer::from($data['netIntangibleAssets']);
        $container->tangibleAssets = FinancialStatementItemContainer::from($data['tangibleAssets']);
        $container->shortTermInvestments = FinancialStatementItemContainer::from($data['shortTermInvestments']);
        $container->currentAccountAndReceivables = FinancialStatementItemContainer::from($data['currentAccountAndReceivables']);
        $container->totalAssets = FinancialStatementItemContainer::from($data['totalAssets']);
        $container->totalEquity = FinancialStatementItemContainer::from($data['totalEquity']);
        $container->currentPortionOfLongTermDebt = FinancialStatementItemContainer::from($data['currentPortionOfLongTermDebt']);
        $container->totalCurrentLiabilities = FinancialStatementItemContainer::from($data['totalCurrentLiabilities']);
        $container->longTermDebt = FinancialStatementItemContainer::from($data['longTermDebt']);
        $container->totalLiabilities = FinancialStatementItemContainer::from($data['totalLiabilities']);

        return $container;
    }
}
