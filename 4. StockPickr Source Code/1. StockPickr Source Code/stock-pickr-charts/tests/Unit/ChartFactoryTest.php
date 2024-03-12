<?php

namespace Tests\Unit;

use App\ChartConfigs\CashFlowChartConfig;
use App\ChartConfigs\CashFlowRevenueChartConfig;
use App\ChartConfigs\ChartConfig;
use App\ChartConfigs\ChartFactory;
use App\ChartConfigs\EpsChartConfig;
use App\ChartConfigs\GrowthChartConfig;
use App\ChartConfigs\IncomeChartConfig;
use App\ChartConfigs\LiquidityChartConfig;
use App\ChartConfigs\MarginsChartConfig;
use App\ChartConfigs\RevenueChartConfig;
use App\ChartConfigs\SolvencyChartConfig;
use Tests\TestCase;

class ChartFactoryTest extends TestCase
{
    private ChartFactory $chartFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->chartFactory = resolve(ChartFactory::class);
    }

    /** @test */
    public function it_should_create_charts_for_summary_group()
    {
        $charts = collect($this->chartFactory->createGroup('summary'));

        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof RevenueChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof IncomeChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof MarginsChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof CashFlowChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof GrowthChartConfig));

        $this->assertCount(5, $charts);
    }

    /** @test */
    public function it_should_create_charts_for_income_statement_group()
    {
        $charts = collect($this->chartFactory->createGroup('income-statement'));

        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof EpsChartConfig));
        $this->assertCount(1, $charts);
    }

    /** @test */
    public function it_should_create_charts_for_income_balance_sheet_group()
    {
        $charts = collect($this->chartFactory->createGroup('balance-sheet'));

        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof SolvencyChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof LiquidityChartConfig));

        $this->assertCount(2, $charts);
    }

    /** @test */
    public function it_should_create_charts_for_cash_flow_group()
    {
        $charts = collect($this->chartFactory->createGroup('cash-flow'));

        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof CashFlowRevenueChartConfig));
        $this->assertNotNull($charts->first(fn (ChartConfig $chart) => $chart instanceof CashFlowChartConfig));

        $this->assertCount(2, $charts);
    }

    /** @test */
    public function it_should_return_all_charts()
    {
        $charts = collect($this->chartFactory->createGroup('all'));
        $this->assertCount(9, $charts);
    }
}
