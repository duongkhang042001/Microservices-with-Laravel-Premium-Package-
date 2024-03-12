<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreOperatingCashFlowToCurrentLiabilitiesTestTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_operating_cash_flow_to_current_liabilities()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'operatingCashFlow' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ]
            ],
            'balanceSheets' => [
                'totalCurrentLiabilities' => [
                    2020 => 100,
                    2019 => 150,
                    2018 => 50,
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
            'operating_cash_flow_to_current_liabilities' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'operating_cash_flow_to_current_liabilities' => 0.6667,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'operating_cash_flow_to_current_liabilities' => 2.0000,
        ]);
    }

    /** @test */
    public function it_should_store_operating_cash_flow_to_current_liabilities_as_0_if_no_operating_cash_flow()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'operatingCashFlow' => [
                    2020 => -100,
                    2019 => 100,
                ]
            ],
            'balanceSheets' => [
                'totalCurrentLiabilities' => [
                    2020 => 100,
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

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'operating_cash_flow_to_current_liabilities' => 0,
        ]);
    }

    /** @test */
    public function it_should_store_operating_cash_flow_to_current_liabilities_as_infinite_if_no_current_liabilities()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'operatingCashFlow' => [
                    2020 => 100,
                    2019 => 10,
                ]
            ],
            'balanceSheets' => [
                'totalCurrentLiabilities' => [
                    2020 => 0,
                    2019 => 10,
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

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'operating_cash_flow_to_current_liabilities' => MetricService::INFINITE_VALUE,
        ]);
    }
}
