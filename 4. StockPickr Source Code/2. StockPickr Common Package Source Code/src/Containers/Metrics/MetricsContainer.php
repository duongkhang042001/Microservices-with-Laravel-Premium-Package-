<?php

namespace StockPickr\Common\Containers\Metrics;

use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementContainer;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;

final class MetricsContainer extends FinancialStatementContainer
{
    protected array $items = [
        'debtToCapital',
        'currentRatio',
        'quickRatio',
        'cashToDebt',
        'interestToOperatingProfit',
        'longTermDebtToEbitda',
        'interestCoverageRatio',
        'debtToAssets',
        'operatingCashFlowToCurrentLiabilities',
        'capexAsPercentOfRevenue',
        'capexAsPercentOfOperatingCashFlow',
        'payoutRatio',
        'roic',
        'croic',
        'rota',
        'roa',
        'roe',
        'freeCashFlowToRevenue',
        'netMargin',
        'operatingMargin',
        'grossMargin',
        'operatingCashFlowMargin',
        'sgaToGrossProfit',
        'epsGrowth',
        'netIncomeGrowth',
        'totalRevenueGrowth',
    ];

    public FinancialStatementItemContainer $debtToCapital;
    public FinancialStatementItemContainer $currentRatio;
    public FinancialStatementItemContainer $quickRatio;
    public FinancialStatementItemContainer $cashToDebt;
    public FinancialStatementItemContainer $interestToOperatingProfit;
    public FinancialStatementItemContainer $longTermDebtToEbitda;
    public FinancialStatementItemContainer $interestCoverageRatio;
    public FinancialStatementItemContainer $debtToAssets;
    public FinancialStatementItemContainer $operatingCashFlowToCurrentLiabilities;
    public FinancialStatementItemContainer $capexAsPercentOfRevenue;
    public FinancialStatementItemContainer $capexAsPercentOfOperatingCashFlow;
    public FinancialStatementItemContainer $payoutRatio;
    public FinancialStatementItemContainer $roic;
    public FinancialStatementItemContainer $croic;
    public FinancialStatementItemContainer $rota;
    public FinancialStatementItemContainer $roa;
    public FinancialStatementItemContainer $roe;
    public FinancialStatementItemContainer $freeCashFlowToRevenue;
    public FinancialStatementItemContainer $netMargin;
    public FinancialStatementItemContainer $operatingMargin;
    public FinancialStatementItemContainer $grossMargin;
    public FinancialStatementItemContainer $operatingCashFlowMargin;
    public FinancialStatementItemContainer $sgaToGrossProfit;
    public FinancialStatementItemContainer $epsGrowth;
    public FinancialStatementItemContainer $netIncomeGrowth;
    public FinancialStatementItemContainer $totalRevenueGrowth;

    public static function from(array $data): MetricsContainer
    {
        $container = new static();

        $container->debtToCapital = FinancialStatementItemContainer::from($data['debtToCapital']);
        $container->currentRatio = FinancialStatementItemContainer::from($data['currentRatio']);
        $container->quickRatio = FinancialStatementItemContainer::from($data['quickRatio']);
        $container->cashToDebt = FinancialStatementItemContainer::from($data['cashToDebt']);
        $container->interestToOperatingProfit = FinancialStatementItemContainer::from($data['interestToOperatingProfit']);
        $container->longTermDebtToEbitda = FinancialStatementItemContainer::from($data['longTermDebtToEbitda']);
        $container->interestCoverageRatio = FinancialStatementItemContainer::from($data['interestCoverageRatio']);
        $container->debtToAssets = FinancialStatementItemContainer::from($data['debtToAssets']);
        $container->operatingCashFlowToCurrentLiabilities = FinancialStatementItemContainer::from($data['operatingCashFlowToCurrentLiabilities']);
        $container->capexAsPercentOfRevenue = FinancialStatementItemContainer::from($data['capexAsPercentOfRevenue']);
        $container->capexAsPercentOfOperatingCashFlow = FinancialStatementItemContainer::from($data['capexAsPercentOfOperatingCashFlow']);
        $container->payoutRatio = FinancialStatementItemContainer::from($data['payoutRatio']);
        $container->roic = FinancialStatementItemContainer::from($data['roic']);
        $container->croic = FinancialStatementItemContainer::from($data['croic']);
        $container->rota = FinancialStatementItemContainer::from($data['rota']);
        $container->roa = FinancialStatementItemContainer::from($data['roa']);
        $container->roe = FinancialStatementItemContainer::from($data['roe']);
        $container->freeCashFlowToRevenue = FinancialStatementItemContainer::from($data['freeCashFlowToRevenue']);
        $container->netMargin = FinancialStatementItemContainer::from($data['netMargin']);
        $container->operatingMargin = FinancialStatementItemContainer::from($data['operatingMargin']);
        $container->grossMargin = FinancialStatementItemContainer::from($data['grossMargin']);
        $container->operatingCashFlowMargin = FinancialStatementItemContainer::from($data['operatingCashFlowMargin']);
        $container->sgaToGrossProfit = FinancialStatementItemContainer::from($data['sgaToGrossProfit']);
        $container->epsGrowth = FinancialStatementItemContainer::from($data['epsGrowth']);
        $container->netIncomeGrowth = FinancialStatementItemContainer::from($data['netIncomeGrowth']);
        $container->totalRevenueGrowth = FinancialStatementItemContainer::from($data['totalRevenueGrowth']);

        return $container;
    }
}