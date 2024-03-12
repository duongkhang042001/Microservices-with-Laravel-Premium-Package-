<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class RevenueChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['totalRevenue', 'grossProfit', 'operatingIncome'];
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
        return 'Revenue';
    }

    public function slug(): string
    {
        return 'revenue';
    }
}
