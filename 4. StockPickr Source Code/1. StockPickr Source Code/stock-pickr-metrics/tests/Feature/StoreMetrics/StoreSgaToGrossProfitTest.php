<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreSgaToGrossProfitTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_sga_to_gross_profit()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'sellingGeneralAdministrative' => [
                    2020 => 300,
                    2019 => 200,
                    2018 => 100,
                ],
                'grossProfit' => [
                    2020 => 1000,
                    2019 => 1000,
                    2018 => 1000,
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
            'sga_to_gross_profit' => 0.3,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'sga_to_gross_profit' => 0.2,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'sga_to_gross_profit' => 0.1,
        ]);
    }

    /** @test */
    public function it_should_store_sga_to_gross_profit_as_1_if_gross_profit_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'incomeStatements' => [
                'sellingGeneralAdministrative' => [
                    2020 => 300,
                    2019 => 100,
                ],
                'grossProfit' => [
                    2020 => -1000,
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
            'sga_to_gross_profit' => 1,
        ]);
    }
}
