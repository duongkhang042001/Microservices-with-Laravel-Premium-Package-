<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreDebtToAssetsTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_debt_to_assets()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 50,
                    2019 => 50,
                    2018 => 50,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 50,
                    2019 => 40,
                    2018 => 70,
                ],
                'totalAssets' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ]
            ],
            'incomeStatements' => [
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
            'debt_to_assets' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'debt_to_assets' => 0.9000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'debt_to_assets' => 1.2000,
        ]);
    }

    /** @test */
    public function it_should_store_debt_to_assets_as_0_if_no_debt()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 0,
                    2019 => 100,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 0,
                    2019 => 100,
                ],
                'totalAssets' => [
                    2020 => 100,
                    2019 => 100,
                ]
            ],
            'incomeStatements' => [
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
            'debt_to_assets' => 0.0000,
        ]);
    }
}
