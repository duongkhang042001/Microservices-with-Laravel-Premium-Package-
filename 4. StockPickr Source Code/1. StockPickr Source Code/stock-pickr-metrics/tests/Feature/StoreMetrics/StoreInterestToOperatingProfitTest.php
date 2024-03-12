<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreInterestToOperatingProfitTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_create_interest_to_operating_profit()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => -100,
                    2019 => -20,
                    2018 => -120,
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
            'interest_to_operating_profit' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_to_operating_profit' => 0.2000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'interest_to_operating_profit' => 1.2000,
        ]);
    }

    /** @test */
    public function it_should_store_as_infinite_if_operating_profit_is_zero_or_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => -100,
                    2019 => -100,
                ],
                'operatingIncome' => [
                    2020 => 0,
                    2019 => -10,
                ],
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
            'interest_to_operating_profit' => MetricService::INFINITE_VALUE,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_to_operating_profit' => MetricService::INFINITE_VALUE,
        ]);
    }

    /** @test */
    public function it_should_store_as_0_if_interest_is_zero()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => 0,
                    2019 => 0,
                ],
                'operatingIncome' => [
                    2020 => 100,
                    2019 => 0,
                ],
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
            'interest_to_operating_profit' => 0.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_to_operating_profit' => 0.0000,
        ]);
    }

    /** @test */
    public function it_should_store_as_0_if_interest_is_positive()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => 100,
                    2019 => 100,
                ],
                'operatingIncome' => [
                    2020 => 100,
                    2019 => 0,
                ],
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
            'interest_to_operating_profit' => 0.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_to_operating_profit' => 0.0000,
        ]);
    }

    /** @test */
    public function it_should_store_as_null_if_interest_is_positive_but_operating_profit_is_negatvive()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => 100,
                    2019 => 0,
                ],
                'operatingIncome' => [
                    2020 => -10,
                    2019 => -10,
                ],
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
            'interest_to_operating_profit' => null,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_to_operating_profit' => null,
        ]);
    }
}
