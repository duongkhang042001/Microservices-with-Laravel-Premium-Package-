<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreInterestCoverageRatioTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_create_interest_coverage_ratio()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => -100,
                    2019 => -20,
                    2018 => -100,
                ],
                'ebit' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 80,
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
            'interest_coverage_ratio' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_coverage_ratio' => 5.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'interest_coverage_ratio' => 0.8000,
        ]);
    }

    /** @test */
    public function it_should_store_coverage_as_infinite_if_interest_and_ebit_positive()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => 100,
                    2019 => 100,
                ],
                'ebit' => [
                    2020 => 100,
                    2019 => 100,
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
            'interest_coverage_ratio' => MetricService::INFINITE_VALUE,
        ]);
    }

    /** @test */
    public function it_should_store_coverage_as_null_if_interest_positive_ebit_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => 100,
                    2019 => 100,
                ],
                'ebit' => [
                    2020 => -100,
                    2019 => 100,
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
            'interest_coverage_ratio' => null,
        ]);
    }

    /** @test */
    public function it_should_store_coverage_as_negative_if_interest_and_ebit_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'interestExpense' => [
                    2020 => -100,
                    2019 => -100,
                    2018 => -500,
                ],
                'ebit' => [
                    2020 => -100,
                    2019 => -500,
                    2018 => -250,
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
            'interest_coverage_ratio' => -1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'interest_coverage_ratio' => -5,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'interest_coverage_ratio' => -0.5,
        ]);
    }
}
