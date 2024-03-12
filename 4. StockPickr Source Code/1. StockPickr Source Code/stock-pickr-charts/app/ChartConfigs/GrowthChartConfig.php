<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class GrowthChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['totalRevenueGrowth', 'netIncomeGrowth', 'epsGrowth'];
    }

    public function type(): string
    {
        return ChartService::TYPE_LINE;
    }

    public function normalizer(): string
    {
        return ChartService::NORMALIZER_PERCENT;
    }

    public function title(): string
    {
        return 'Growth';
    }

    public function slug(): string
    {
        return 'growth';
    }
}
