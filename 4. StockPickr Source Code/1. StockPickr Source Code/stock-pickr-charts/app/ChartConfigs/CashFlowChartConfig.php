<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class CashFlowChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['operatingCashFlow', 'capex', 'freeCashFlow'];
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
        return 'Cash Flow';
    }

    public function slug(): string
    {
        return 'cashFlow';
    }
}
