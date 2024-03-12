<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreOperatingCashFlowMarginTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_operating_cash_flow_margin()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'operatingCashFlow' => [
                    2020 => 300,
                    2019 => 200,
                    2018 => 100,
                ],
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
            'operating_cash_flow_margin' => 0.3,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'operating_cash_flow_margin' => 0.2,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'operating_cash_flow_margin' => 0.1,
        ]);
    }

    /** @test */
    public function it_should_store_operating_cash_flow_margin_as_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'operatingCashFlow' => [
                    2020 => -300,
                    2019 => 100,
                ],
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
        $this->assertEquals(-0.3, round($companyMetric->operating_cash_flow_margin, 1));
    }
}
