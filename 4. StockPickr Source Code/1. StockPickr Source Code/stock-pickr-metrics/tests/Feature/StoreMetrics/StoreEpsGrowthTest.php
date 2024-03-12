<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreEpsGrowthTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_null_for_the_first_year()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'eps' => [
                    2020 => 121,
                    2019 => 110,
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
            'year' => 2018,
            'eps_growth' => null,
        ]);
    }

    /** @test */
    public function it_should_store_yearly_eps_growth()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'eps' => [
                    2020 => 121,
                    2019 => 110,
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
            'eps_growth' => 0.1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'eps_growth' => 0.1,
        ]);
    }

    /** @test */
    public function it_should_store_eps_growth_as_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'eps' => [
                    2020 => 100,
                    2019 => 110,
                    2018 => 121,
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
        $this->assertEquals(-0.1, round($companyMetric->eps_growth, 1));

        $companyMetric = $this->getCompanyMetric(2019);
        $this->assertEquals(-0.1, round($companyMetric->eps_growth, 1));
    }

    /** @test */
    public function it_should_store_eps_growth_as_null_if_previous_year_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'eps' => [
                    2020 => 10,
                    2019 => -121,
                    2018 => -110,
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
            'eps_growth' => null,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'eps_growth' => null,
        ]);
    }

    /** @test */
    public function it_should_store_eps_growth_as_null_if_previous_year_0()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'eps' => [
                    2020 => 10,
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
            'eps_growth' => null,
        ]);
    }
}
