<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class LiquidityChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['cash', 'totalCurrentAssets', 'totalCurrentLiabilities'];
    }

    public function type(): string
    {
        return ChartService::TYPE_BAR;
    }

    public function normalizer(): string
    {
        return ChartService::NORMALIZER_FINANCIAL_NUMBER;
    }

    public function title(): string
    {
        return 'Liquidity';
    }

    public function slug(): string
    {
        return 'liquidity';
    }
}
