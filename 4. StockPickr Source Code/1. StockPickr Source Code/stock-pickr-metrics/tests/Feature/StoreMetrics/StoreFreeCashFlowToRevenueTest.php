<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreFreeCashFlowToRevenueTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_free_cash_flow_to_revenue()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'freeCashFlow' => [
                    2020 => 100,
                    2019 => 150,
                    2018 => 200
                ]
            ],
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                    2018 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'free_cash_flow_to_revenue' => 0.1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'free_cash_flow_to_revenue' => 0.15,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'free_cash_flow_to_revenue' => 0.2,
        ]);
    }

    /** @test */
    public function it_should_store_free_cash_flow_to_revenue_as_negative_if_cash_flow_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'freeCashFlow' => [
                    2020 => -100,
                    2019 => 100,
                ]
            ],
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        // MySQL kerekítés miatt vannak ilyen assertek használva
        $companyMetric = $this->getCompanyMetric(2020);
        $this->assertEquals(-0.1, round($companyMetric->free_cash_flow_to_revenue, 1));
    }
}
