<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreRoicTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_roic()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 50,
                    2019 => 50,
                    2018 => 50,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 20,
                    2018 => 10,
                ],
                'cash' => [
                    2020 => 10,
                    2019 => 10,
                    2018 => 10,
                ],
                'totalEquity' => [
                    2020 => 10,
                    2019 => 20,
                    2018 => 30,
                ]
            ],
            'incomeStatements' => [
                'pretaxIncome' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ],
                'incomeTax' => [
                    2020 => 90,
                    2019 => 85,
                    2018 => 80,
                ],
                'operatingIncome' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
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
            'roic' => 0.1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'roic' => 0.15,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'roic' => 0.2,
        ]);
    }

    /** @test */
    public function it_should_store_roic_as_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 50,
                    2019 => 50,
                    2018 => 50,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 20,
                    2018 => 10,
                ],
                'cash' => [
                    2020 => 10,
                    2019 => 10,
                    2018 => 10,
                ],
                'totalEquity' => [
                    2020 => 10,
                    2019 => 20,
                    2018 => 30,
                ]
            ],
            'incomeStatements' => [
                'pretaxIncome' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ],
                'incomeTax' => [
                    2020 => 90,
                    2019 => 85,
                    2018 => 80,
                ],
                'operatingIncome' => [
                    2020 => -100,
                    2019 => -100,
                    2018 => -100,
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
        $this->assertEquals(-0.1, round($companyMetric->roic, 1));

        $companyMetric = $this->getCompanyMetric(2019);
        $this->assertEquals(-0.15, round($companyMetric->roic, 2));

        $companyMetric = $this->getCompanyMetric(2018);
        $this->assertEquals(-0.20, round($companyMetric->roic, 2));
    }
}
