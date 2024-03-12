<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class SolvencyChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['cash', 'longTermDebt', 'totalEquity'];
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
        return 'Solvency';
    }

    public function slug(): string
    {
        return 'solvency';
    }
}
