<?php

namespace App\ChartConfigs;

use App\Services\ChartService;

class MarginsChartConfig extends ChartConfig
{
    public function financials(): array
    {
        return ['grossMargin', 'operatingMargin', 'netMargin'];
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
        return 'Margins';
    }

    public function slug(): string
    {
        return 'margins';
    }
}
