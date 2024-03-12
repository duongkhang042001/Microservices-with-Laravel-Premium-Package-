<?php

namespace Tests\Feature\Events;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCompanyMetricMediansTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_metric_medians()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'totalAssets' => [
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

        $this->assertDatabaseHas('company_metric_medians', [
            'ticker' => 'TST',
            'roa' => 0.15,
        ]);
    }
}
