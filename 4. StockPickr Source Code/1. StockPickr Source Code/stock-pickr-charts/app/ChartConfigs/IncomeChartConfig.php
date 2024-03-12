<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class IncomeChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['netIncome'];
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
        return 'Income';
    }

    public function slug(): string
    {
        return 'income';
    }
}
