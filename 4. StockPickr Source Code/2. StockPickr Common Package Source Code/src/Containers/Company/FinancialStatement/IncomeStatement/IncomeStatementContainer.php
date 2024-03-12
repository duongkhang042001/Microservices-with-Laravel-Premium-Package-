<?php

namespace StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement;

use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;

final class IncomeStatementContainer extends FinancialStatementContainer
{
    protected array $items = [
        'totalRevenue',
        'costOfRevenue',
        'grossProfit',
        'operatingIncome',
        'pretaxIncome',
        'incomeTax',
        'interestExpense',
        'researchAndDevelopment',
        'sellingGeneralAdministrative',
        'netIncome',
        'ebit',
        'eps',
    ];
    
    public FinancialStatementItemContainer $totalRevenue;
    public FinancialStatementItemContainer $costOfRevenue;
    public FinancialStatementItemContainer $grossProfit;
    public FinancialStatementItemContainer $operatingIncome;
    public FinancialStatementItemContainer $pretaxIncome;
    public FinancialStatementItemContainer $incomeTax;
    public FinancialStatementItemContainer $interestExpense;
    public FinancialStatementItemContainer $researchAndDevelopment;
    public FinancialStatementItemContainer $sellingGeneralAdministrative;
    public FinancialStatementItemContainer $netIncome;
    public FinancialStatementItemContainer $ebit;
    public FinancialStatementItemContainer $eps;

    public static function from(array $data): IncomeStatementContainer
    {
        $container = new static();

        $container->totalRevenue = FinancialStatementItemContainer::from($data['totalRevenue']);
        $container->costOfRevenue = FinancialStatementItemContainer::from($data['costOfRevenue']);
        $container->grossProfit = FinancialStatementItemContainer::from($data['grossProfit']);
        $container->operatingIncome = FinancialStatementItemContainer::from($data['operatingIncome']);
        $container->pretaxIncome = FinancialStatementItemContainer::from($data['pretaxIncome']);
        $container->incomeTax = FinancialStatementItemContainer::from($data['incomeTax']);
        $container->interestExpense = FinancialStatementItemContainer::from($data['interestExpense']);
        $container->researchAndDevelopment = FinancialStatementItemContainer::from($data['researchAndDevelopment']);
        $container->sellingGeneralAdministrative = FinancialStatementItemContainer::from($data['sellingGeneralAdministrative']);
        $container->netIncome = FinancialStatementItemContainer::from($data['netIncome']);
        $container->ebit = FinancialStatementItemContainer::from($data['ebit']);
        $container->eps = FinancialStatementItemContainer::from($data['eps']);

        return $container;
    }
}
