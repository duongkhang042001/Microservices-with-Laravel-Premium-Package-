<?php

namespace Tests\Feature\Events;

use App\Events\CompanyUpserted;
use App\Models\Company;
use App\Models\CompanyMetricMedian;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCompanyMetricMediansTest extends TestCase
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
        $company = Company::factory(['ticker' => 'TST'])->create();
        CompanyMetricMedian::factory()
            ->state([
                'ticker' => 'TST',
                'company_id' => $company->id,
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

        $this->assertDatabaseCount('company_metric_medians', 1);
        $this->assertDatabaseHas('company_metric_medians', [
            'ticker' => 'TST',
            'net_margin' => 0.225
        ]);
    }
}
