<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreLongTermDebtToEbitdaTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_long_term_debt_to_ebitda()
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
                    2019 => 60,
                    2018 => 40,
                ],
            ],
            'incomeStatements' => [
                'ebit' => [
                    2020 => 50,
                    2019 => 50,
                    2018 => 50,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                    2018 => 1000,
                ]
            ],
            'cashFlows' => [
                'deprecationAmortization' => [
                    2020 => 50,
                    2019 => 50,
                    2018 => 50,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'long_term_debt_to_ebitda' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'long_term_debt_to_ebitda' => 1.1000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'long_term_debt_to_ebitda' => 0.9000,
        ]);
    }

    /** @test */
    public function it_should_store_as_infinite_if_no_ebitda()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 50,
                    2019 => 50,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 50,
                    2019 => 60,
                ],
            ],
            'incomeStatements' => [
                'ebit' => [
                    2020 => -50,
                    2019 => -500,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ],
            'cashFlows' => [
                'deprecationAmortization' => [
                    2020 => 50,
                    2019 => 50,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'long_term_debt_to_ebitda' => MetricService::INFINITE_VALUE,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'long_term_debt_to_ebitda' => MetricService::INFINITE_VALUE,
        ]);
    }

    /** @test */
    public function it_should_store_as_0_if_no_debt()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 0,
                    2019 => 0,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 0,
                    2019 => 0,
                ],
            ],
            'incomeStatements' => [
                'ebit' => [
                    2020 => 50,
                    2019 => -50,
                ],
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 1000,
                ]
            ],
            'cashFlows' => [
                'deprecationAmortization' => [
                    2020 => 50,
                    2019 => 50,
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'long_term_debt_to_ebitda' => 0.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'long_term_debt_to_ebitda' => 0.0000,
        ]);
    }
}
