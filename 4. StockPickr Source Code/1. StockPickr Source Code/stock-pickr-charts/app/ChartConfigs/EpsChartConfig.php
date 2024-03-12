<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class EpsChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['eps'];
    }

    public function type(): string
    {
        return ChartService::TYPE_BAR;
    }

    public function normalizer(): string
    {
        return ChartService::NORMALIZER_MONEY;
    }

    public function title(): string
    {
        return 'Earnings Per Share';
    }

    public function slug(): string
    {
        return 'eps';
    }
}
