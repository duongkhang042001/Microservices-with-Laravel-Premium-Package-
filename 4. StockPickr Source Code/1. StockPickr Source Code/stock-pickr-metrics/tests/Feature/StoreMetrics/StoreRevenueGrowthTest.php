<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreRevenueGrowthTest extends TestCase
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
                'totalRevenue' => [
                    2020 => 1210,
                    2019 => 1100,
                    2018 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'total_revenue_growth' => null,
        ]);
    }

    /** @test */
    public function it_should_store_yearly_total_revenue_growth()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 1210,
                    2019 => 1100,
                    2018 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'total_revenue_growth' => 0.1,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'total_revenue_growth' => 0.1,
        ]);
    }

    /** @test */
    public function it_should_store_total_revenue_growth_as_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1100,
                    2018 => 1210,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        // MySQL kerekítés miatt vannak ilyen assertek használva
        $companyMetric = $this->getCompanyMetric(2020);
        $this->assertEquals(-0.1, round($companyMetric->total_revenue_growth, 1));

        $companyMetric = $this->getCompanyMetric(2019);
        $this->assertEquals(-0.1, round($companyMetric->total_revenue_growth, 1));
    }
}
