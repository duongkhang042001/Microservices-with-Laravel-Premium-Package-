<?php

namespace Tests\Feature\UpdateMetrics;

use App\Events\CompanyUpserted;
use App\Models\CompanyMetric;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMetricsTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_update_existing_metrics()
    {
        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST',
                'year' => 2020,
                'net_margin' => 0.30
            ])
            ->create();

        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'netIncome' => [
                    2020 => 350,
                    2019 => 100,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, true));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'net_margin' => 0.35
        ]);
    }

    /** @test */
    public function it_should_create_new_records_and_keep_untouched_ones()
    {
        CompanyMetric::factory()
            ->state([
                'ticker' => 'TST',
                'year' => 2019,
                'net_margin' => 0.30
            ])
            ->create();

        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'netIncome' => [
                    2020 => 350,
                    2019 => 300
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, true));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'net_margin' => 0.30
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'net_margin' => 0.35
        ]);
    }

    /** @test */
    public function it_should_not_alter_other_companies()
    {
        CompanyMetric::factory()
            ->state([
                'ticker' => 'OTHR',
                'year' => 2019,
                'net_margin' => 0.20
            ])
            ->create();

        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'netIncome' => [
                    2020 => 100,
                    2019 => 300,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, true));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'net_margin' => 0.30
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'OTHR',
            'year' => 2019,
            'net_margin' => 0.20
        ]);
    }
}
