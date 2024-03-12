<?php

namespace Tests\Feature\StoreMetrics;

use App\Events\CompanyUpserted;
use App\Services\MetricService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateMetricsTest extends TestCase
{
    use RefreshDatabase;

    private MetricService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(MetricService::class);
    }

    /** @test */
    public function it_should_store_debt_to_capital()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 70,
                    2019 => 40
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 40
                ],
                'totalEquity' => [
                    2020 => 100,
                    2019 => 70
                ]
            ],
            'incomeStatements' => [
                'totalRevenue' => [
                    2020 => 1000,
                    2019 => 872
                ]
            ]
        ]);

        event(new CompanyUpserted($company, false));

        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2020,
            'debt_to_capital' => 0.5000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'debt_to_capital' => 0.5333,
        ]);
    }

    /** @test */
    public function it_should_store_debt_to_equity_as_1_if_equity_is_zero()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 70,
                    2019 => 100,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 100,
                ],
                'totalEquity' => [
                    2020 => 0,
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
            'debt_to_capital' => 1.0000,
        ]);
    }

    /** @test */
    public function it_should_store_debt_to_equity_as_1_if_equity_is_negative()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 70,
                    2019 => 100,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 100,
                ],
                'totalEquity' => [
                    2020 => -50,
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
            'debt_to_capital' => 1.0000,
        ]);
    }

    /** @test */
    public function it_should_store_debt_to_equity_as_0_if_debt_is_zero()
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
                'totalEquity' => [
                    2020 => 10,
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
            'debt_to_capital' => 0.0000,
        ]);
    }

    /** @test */
    public function it_should_store_current_ratio()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'totalCurrentAssets' => [
                    2020 => 100,
                    2019 => 70,
                    2018 => 120,
                ],
                'totalCurrentLiabilities' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ],
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
            'current_ratio' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'current_ratio' => 0.7000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'current_ratio' => 1.2000,
        ]);
    }

    /** @test */
    public function it_should_store_current_ratio_as_infinite_if_current_liabilities_is_zero()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'totalCurrentAssets' => [
                    2020 => 100,
                    2019 => 100,
                ],
                'totalCurrentLiabilities' => [
                    2020 => 0,
                    2019 => 100,
                ],
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
            'current_ratio' => MetricService::INFINITE_VALUE
        ]);
    }

    /** @test */
    public function it_should_store_quick_ratio()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'currentAccountAndReceivables' => [
                    2020 => 100,
                    2019 => 70,
                    2018 => 120,
                ],
                'cash' => [
                    2020 => 50,
                    2019 => 30,
                    2018 => 30,
                ],
                'totalCurrentLiabilities' => [
                    2020 => 150,
                    2019 => 50,
                    2018 => 300,
                ],
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
            'quick_ratio' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'quick_ratio' => 2.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'quick_ratio' => 0.5000,
        ]);
    }

    /** @test */
    public function it_should_store_quick_ratio_as_infinite_if_current_liabilities_is_zero()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'currentAccountAndReceivables' => [
                    2020 => 100,
                    2019 => 100,
                ],
                'cash' => [
                    2020 => 50,
                    2019 => 100,
                ],
                'totalCurrentLiabilities' => [
                    2020 => 0,
                    2019 => 100,
                ],
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
            'quick_ratio' => MetricService::INFINITE_VALUE,
        ]);
    }

    /** @test */
    public function it_should_store_cash_to_debt()
    {
        $company = $this->createCompanyUpsertedContainer([
            'balanceSheets' => [
                'longTermDebt' => [
                    2020 => 70,
                    2019 => 40,
                    2018 => 100,
                ],
                'currentPortionOfLongTermDebt' => [
                    2020 => 30,
                    2019 => 40,
                    2018 => 100,
                ],
                'cash' => [
                    2020 => 100,
                    2019 => 100,
                    2018 => 100,
                ],
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
            'cash_to_debt' => 1.0000,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2019,
            'cash_to_debt' => 1.2500,
        ]);
        $this->assertDatabaseHas('company_metrics', [
            'ticker' => 'TST',
            'year' => 2018,
            'cash_to_debt' => 0.5000,
        ]);
    }

    /** @test */
    public function it_should_store_cash_to_debt_as_1_if_no_debt()
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
                'cash' => [
                    2020 => 100,
                    2019 => 100,
                ],
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
            'cash_to_debt' => 1.0000,
        ]);
    }
}
