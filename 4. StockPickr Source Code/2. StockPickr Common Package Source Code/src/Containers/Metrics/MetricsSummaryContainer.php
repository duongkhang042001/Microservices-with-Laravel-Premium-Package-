<?php

namespace StockPickr\Common\Containers\Metrics;

use StockPickr\Common\Containers\Container;

final class MetricsSummaryContainer extends Container
{
    public float $debtToCapital;
    public float $currentRatio;
    public float $quickRatio;
    public float $cashToDebt;
    public ?float $interestToOperatingProfit;
    public float $longTermDebtToEbitda;
    public ?float $interestCoverageRatio;
    public float $debtToAssets;
    public float $operatingCashFlowToCurrentLiabilities;
    public float $capexAsPercentOfRevenue;
    public float $capexAsPercentOfOperatingCashFlow;
    public ?float $payoutRatio;
    public float $roic;
    public float $croic;
    public float $rota;
    public float $roa;
    public float $roe;
    public float $freeCashFlowToRevenue;
    public float $netMargin;
    public float $operatingMargin;
    public float $grossMargin;
    public float $operatingCashFlowMargin;
    public float $sgaToGrossProfit;
    public ?float $epsGrowth;
    public ?float $netIncomeGrowth;
    public ?float $totalRevenueGrowth;

    public static function from(array $data): MetricsSummaryContainer
    {
        $container = new static();

        $container->debtToCapital = $data['debtToCapital'];
        $container->currentRatio = $data['currentRatio'];
        $container->quickRatio = $data['quickRatio'];
        $container->cashToDebt = $data['cashToDebt'];
        $container->interestToOperatingProfit = $data['interestToOperatingProfit'];
        $container->longTermDebtToEbitda = $data['longTermDebtToEbitda'];
        $container->interestCoverageRatio = $data['interestCoverageRatio'];
        $container->debtToAssets = $data['debtToAssets'];
        $container->operatingCashFlowToCurrentLiabilities = $data['operatingCashFlowToCurrentLiabilities'];
        $container->capexAsPercentOfRevenue = $data['capexAsPercentOfRevenue'];
        $container->capexAsPercentOfOperatingCashFlow = $data['capexAsPercentOfOperatingCashFlow'];
        $container->payoutRatio = $data['payoutRatio'];
        $container->roic = $data['roic'];
        $container->croic = $data['croic'];
        $container->rota = $data['rota'];
        $container->roa = $data['roa'];
        $container->roe = $data['roe'];
        $container->freeCashFlowToRevenue = $data['freeCashFlowToRevenue'];
        $container->netMargin = $data['netMargin'];
        $container->operatingMargin = $data['operatingMargin'];
        $container->grossMargin = $data['grossMargin'];
        $container->operatingCashFlowMargin = $data['operatingCashFlowMargin'];
        $container->sgaToGrossProfit = $data['sgaToGrossProfit'];
        $container->epsGrowth = $data['epsGrowth'];
        $container->netIncomeGrowth = $data['netIncomeGrowth'];
        $container->totalRevenueGrowth = $data['totalRevenueGrowth'];

        return $container;
    }
}