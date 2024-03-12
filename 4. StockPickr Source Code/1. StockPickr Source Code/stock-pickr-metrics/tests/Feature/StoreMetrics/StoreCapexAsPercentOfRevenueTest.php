<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCapexAsPercentOfRevenueTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_capex_as_percent_of_revenue()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'capex' => [
                    2020 => -100,
                    2019 => -100,
                    2018 => -100,
                ]
            ],
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 100,
                    2019 => 150,
                    2018 => 50,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'capex_as_percent_of_revenue' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'capex_as_percent_of_revenue' => 0.6667,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'capex_as_percent_of_revenue' => 2.0000,
        ]);
    }

    /** @test */
    public function it_should_store_capex_as_percent_of_revenue_as_0_if_no_capex()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'capex' => [
                    2020 => 0,
                    2019 => 100,
                ]
            ],
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 100,
                    2019 => 100,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'capex_as_percent_of_revenue' => 0,
        ]);
    }
}
