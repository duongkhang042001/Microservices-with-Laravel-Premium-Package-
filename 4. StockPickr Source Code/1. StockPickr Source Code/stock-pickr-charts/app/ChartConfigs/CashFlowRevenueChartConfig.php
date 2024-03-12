<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class CashFlowRevenueChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['totalRevenue', 'freeCashFlow'];
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
        return 'Free Cash Flow to Revenue';
    }

    public function slug(): string
    {
        return 'cashFlowRevenue';
    }
}
