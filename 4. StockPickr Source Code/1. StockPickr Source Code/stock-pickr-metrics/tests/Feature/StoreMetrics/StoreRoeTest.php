<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreRoeTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_roe()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'totalEquity' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100
                ]
            ],
            'incomeStatements' => [
                'netIncome' => [
                    2020 => 10,
                    2019 => 15,
                    2018 => 20,
                ],
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
            'roe' => 0.1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'roe' => 0.15,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'roe' => 0.2,
        ]);
    }

    /** @test */
    public function it_should_store_roe_as_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'totalEquity' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100
                ]
            ],
            'incomeStatements' => [
                'netIncome' => [
                    2020 => -10,
                    2019 => -15,
                    2018 => -20,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                    2018 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        // MySQL kerekítés miatt vannak ilyen assertek használva
        $companyMetric = $this->getCompanyMetric(2020);
        $this->assertEquals(-0.1, round($companyMetric->roe, 1));

        $companyMetric = $this->getCompanyMetric(2019);
        $this->assertEquals(-0.15, round($companyMetric->roe, 2));

        $companyMetric = $this->getCompanyMetric(2018);
        $this->assertEquals(-0.20, round($companyMetric->roe, 2));
    }
}
