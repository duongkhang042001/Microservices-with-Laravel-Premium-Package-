<?php

namespace App\ChartConfigs;

use App\Exceptions\ChartGroupNotFoundException;

class ChartFactory
{
    /**
     * @return ChartConfig[]
     */
    public function createGroup(string $group): array
    {
        if ($group === 'all') {
            return $this->createAll();
        }

        return match($group) {
            'summary'           => [new RevenueChartConfig, new IncomeChartConfig, new MarginsChartConfig, new CashFlowChartConfig, new GrowthChartConfig],
            'income-statement'  => [new EpsChartConfig],
            'balance-sheet'     => [new SolvencyChartConfig, new LiquidityChartConfig],
            'cash-flow'         => [new CashFlowRevenueChartConfig, new CashFlowChartConfig],
            default             => throw new ChartGroupNotFoundException('Chart group not found for ' . $group)
        };
    }

    /**
     * @return ChartConfig[]
     */
    private function createAll(): array
    {
        $groups = ['summary', 'income-statement', 'balance-sheet', 'cash-flow'];
        $charts = [];

        foreach ($groups as $group) {
            array_push($charts, $this->createGroup($group));
        }

        return collect($charts)->flatten()->unique()->all();
    }
}
