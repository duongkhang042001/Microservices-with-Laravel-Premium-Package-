<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorePayoutRatioTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_payout_ratio()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'cashDividendPaid' => [
                    2020 => -100,
                    2019 => -100,
                    2018 => -100,
                ],
                'freeCashFlow' => [
                    2020 => 100,
                    2019 => 150,
                    2018 => 50,
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
            'payout_ratio' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'payout_ratio' => 0.6667,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'payout_ratio' => 2.0000,
        ]);
    }

    /** @test */
    public function it_should_store_payout_ratio_as_1_if_no_fcf()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'cashDividendPaid' => [
                    2020 => -100,
                    2019 => -100,
                ],
                'freeCashFlow' => [
                    2020 => 0,
                    2019 => -200,
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

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'payout_ratio' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'payout_ratio' => 1.0000,
        ]);
    }

    /** @test */
    public function it_should_store_payout_ratio_as_null_if_no_dividend()
    {
        $company = $this->createCompanyUpsertedContainer([
            'cashFlows' => [
                'cashDividendPaid' => [
                    2020 => 0,
                    2019 => 0,
                    2018 => 0,
                ],
                'freeCashFlow' => [
                    2020 => 0,
                    2019 => -200,
                    2018 => 500,
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
            'payout_ratio' => null,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'payout_ratio' => null,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'payout_ratio' => null,
        ]);
    }
}
