<?php

namespace Tests\Feature\Events;

use App\Events\Company\UpsertCompany;
use App\Exceptions\InvalidCompanyException;
use App\Models\Company\Company;
use App\Models\Company\Sector;
use App\Repositories\FinancialStatement\FinancialStatementItemRepository;
use App\Services\RedisService;
use Exception;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

class UpdateCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_publish_the_updated_company()
    {
        $sector = Sector::factory()->state(['name' => 'Tech'])->create();
        Company::factory()
            ->state([
                'ticker'    => 'TST'
            ])
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), true, 'acb-123'));

        $event = $this->getLastEvent();
        $this->assertEquals('TST', $event['data']['ticker']);
        $this->assertEquals('Test Inc.', $event['data']['name']);
        $this->assertEquals('Tech', $event['data']['sector']['name']);
    }

    /** @test */
    public function it_should_publish_the_updated_company_with_financials()
    {
        $sector = Sector::factory()->create();
        Company::factory()
            ->state([
                'ticker'    => 'TST'
            ])
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), true, 'acb-123'));

        $event = $this->getLastEvent();
        $company = CompanyUpsertedContainer::from($event['data']);

        $this->assertEquals(110, $company->getIncomeStatements()->getRawValue('totalRevenue', 2020));
        $this->assertEquals(100, $company->getBalanceSheets()->getRawValue('totalAssets', 2020));
        $this->assertEquals(50, $company->getCashFlows()->getRawValue('operatingCashFlow', 2020));
    }

    /**
     * @test
     * @dataProvider invalidDataProvider()
    */
    public function it_should_throw_exception_if_financial_statement_is_invalid(array $incomeStatements, array $balanceSheets, array $cashFlows)
    {
        Company::factory()
            ->state([
                'ticker'    => 'TST'
            ])
            ->create();

        try {
            $this->mockRedisService('publishCompanyUpdateFailed');
            event(new UpsertCompany($this->createUpserCompanyContainer([
                'ticker'    => 'TST',
                'name'      => 'Test Inc.',
                'sector'    => 'IT',
                'financialStatements'    => [
                    'incomeStatements'  => $incomeStatements,
                    'balanceSheets'     => $balanceSheets,
                    'cashFlows'         => $cashFlows
                ]
            ]), true, 'acb-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

     /** @test */
     public function it_should_update_a_company()
     {
        $oldSector = Sector::factory()->state(['name' => 'Old Sector'])->create();
        $newSector = Sector::factory()->state(['name' => 'New Sector', 'slug' => 'new-sector'])->create();

        Company::factory()
            ->state([
                'ticker'        => 'TST',
                'sector_id'     => $oldSector
            ])
            ->create();

        $this->mockRedisService('publishCompanyUpdated');

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $newSector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), true, 'acb-123'));

        $this->assertDatabaseHas('companies', [
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector_id' => $newSector->id
        ]);
     }

    /** @test */
    public function it_should_update_income_statements_for_a_company()
    {
        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        $id = $event['data']['id'];

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatementsForUpdate(),
                'balanceSheets'     => $this->getBalanceSheetsForUpdate(),
                'cashFlows'         => $this->getCashFlowsForUpdate()
            ]
        ]), true, 'acb-123'));

        $this->assertDatabaseHas('income_statements', [
            'ticker' => 'TST',
            'company_id' => $id,
            'year' => 2021,
            'total_revenue' => 210,
            'net_income' => 200,
            'cost_of_revenue' => 20,
            'gross_profit' => 20,
            'operating_income' => 20,
            'pretax_income' => 20,
            'income_tax' => 20,
            'interest_expense' => 20,
            'research_and_development' => 20,
            'sga' => 20,
            'ebit' => 20,
            'eps' => 20,
        ]);

        $this->assertDatabaseHas('income_statements', [
            'ticker' => 'TST',
            'company_id' => $id,
            'year' => 2020,
            'total_revenue' => 110,
            'net_income' => 100,
            'cost_of_revenue' => 10,
            'gross_profit' => 10,
            'operating_income' => 10,
            'pretax_income' => 10,
            'income_tax' => 10,
            'interest_expense' => 10,
            'research_and_development' => 10,
            'sga' => 10,
            'ebit' => 10,
            'eps' => 10,
        ]);
    }

    /** @test */
    public function it_should_update_balance_sheets_for_a_company()
    {
        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        $id = $event['data']['id'];

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatementsForUpdate(),
                'balanceSheets'     => $this->getBalanceSheetsForUpdate(),
                'cashFlows'         => $this->getCashFlowsForUpdate()
            ]
        ]), true, 'acb-123'));

        $this->assertDatabaseHas('balance_sheets', [
            'ticker' => 'TST',
            'company_id' => $event['data']['id'],
            'year' => 2021,
            'total_assets' => 200,
            'cash' => 20,
            'current_cash' => 20,
            'total_current_assets' => 20,
            'net_intangible_assets' => 20,
            'tangible_assets' => 20,
            'short_term_investments' => 20,
            'tradeaccounts_receivable_current' => 20,
            'total_equity' => 20,
            'current_portion_of_long_term_debt' => 20,
            'total_current_liabilities' => 20,
            'long_term_debt' => 20,
            'total_liabalities' => 20,
        ]);

        $this->assertDatabaseHas('balance_sheets', [
            'ticker' => 'TST',
            'company_id' => $event['data']['id'],
            'year' => 2020,
            'total_assets' => 100,
            'cash' => 10,
            'current_cash' => 10,
            'total_current_assets' => 10,
            'net_intangible_assets' => 10,
            'tangible_assets' => 10,
            'short_term_investments' => 10,
            'tradeaccounts_receivable_current' => 10,
            'total_equity' => 10,
            'current_portion_of_long_term_debt' => 10,
            'total_current_liabilities' => 10,
            'long_term_debt' => 10,
            'total_liabalities' => 10,
        ]);
    }

    /** @test */
    public function it_should_update_cash_flows_for_a_company()
    {
        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        $id = $event['data']['id'];

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => 'IT',
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatementsForUpdate(),
                'balanceSheets'     => $this->getBalanceSheetsForUpdate(),
                'cashFlows'         => $this->getCashFlowsForUpdate()
            ]
        ]), true, 'acb-123'));

        $this->assertDatabaseHas('cash_flows', [
            'ticker' => 'TST',
            'company_id' => $event['data']['id'],
            'year' => 2021,
            'operating_cash_flow' => 100,
            'net_income' => 20,
            'capex' => 20,
            'cash_dividends_paid' => 20,
            'depreciation_amortization' => 20,
            'free_cash_flow' => 20,
            'cash_from_financing' => 20,
            'cash_from_investing' => 20,
        ]);

        $this->assertDatabaseHas('cash_flows', [
            'ticker' => 'TST',
            'company_id' => $event['data']['id'],
            'year' => 2020,
            'operating_cash_flow' => 50,
            'net_income' => 10,
            'capex' => 10,
            'cash_dividends_paid' => 10,
            'depreciation_amortization' => 10,
            'free_cash_flow' => 10,
            'cash_from_financing' => 10,
            'cash_from_investing' => 10,
        ]);
    }

    /** @test */
    public function it_should_update_peers()
    {
        $sector = Sector::factory()->create();
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1'],
                ['ticker' => 'TST2'],
                ['ticker' => 'TST3'],
            ))
            ->count(3)
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'peers' => ['TST1', 'TST2'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'peers' => ['TST2', 'TST3'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), true, 'acb-123'));

        $event = $this->getLastEvent();

        $this->assertCount(2, $event['data']['peers']);
        $this->assertContains('TST2', $event['data']['peers']);
        $this->assertContains('TST3', $event['data']['peers']);
    }

    /** @test */
    public function it_should_publish_company_update_failed_event()
    {
        $this->mock(RedisService::class, function (MockInterface $mock) {
            $mock->shouldReceive('publishCompanyCreated');
            $mock->shouldReceive('publishCompanyUpdateFailed');
        });

        $this->expectException(InvalidCompanyException::class);

        $sector = Sector::factory()->create();
        Company::factory()
            ->state([
                'ticker' => 'TST1'
            ])
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'peers' => ['TST1', 'TST2'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name
        ]), true, 'acb-123'));

    }

    // -------- Helpers --------

    public function invalidDataProvider()
    {
        return [
            [ []                          , $this->getBalanceSheets(), $this->getCashFlows() ],
            [ $this->getIncomeStatements(), []                       , $this->getCashFlows() ],
            [ $this->getIncomeStatements(), $this->getBalanceSheets(), []                    ],
        ];
    }

    private function getIncomeStatements()
    {
        return [
            '2020'  => [
                'totalRevenue' => 110,
                'netIncome' => 100,
                'costOfRevenue' => 10,
                'grossProfit' => 10,
                'researchAndDevelopment' => 10,
                'sga' => 10,
                'operatingIncome' => 10,
                'incomeTax' => 10,
                'pretaxIncome' => 10,
                'ebit' => 10,
                'eps' => 10,
                'interestExpense' => 10
            ]
        ];
    }

    private function getIncomeStatementsForUpdate()
    {
        $data = $this->getIncomeStatements();
        $data['2021'] = [
            'totalRevenue' => 210,
            'netIncome' => 200,
            'costOfRevenue' => 20,
            'grossProfit' => 20,
            'researchAndDevelopment' => 20,
            'sga' => 20,
            'operatingIncome' => 20,
            'incomeTax' => 20,
            'pretaxIncome' => 20,
            'ebit' => 20,
            'eps' => 20,
            'interestExpense' => 20
        ];

        return $data;
    }

    private function getBalanceSheets()
    {
        return [
            '2020'  => [
                'totalAssets' => 100,
                'totalCurrentAssets' => 10,
                'totalCurrentLiabilities' => 10,
                'totalEquity' => 10,
                'longTermDebt' => 10,
                'currentPortionOfLongTermDebt' => 10,
                'shortTermInvestments' => 10,
                'currentCash' => 10,
                'cash' => 10,
                'tradeaccountsReceivableCurrent' => 10,
                'netIntangibleAssets' => 10,
                'tangibleAssets' => 10,
                'totalLiabalities' => 10
            ]
        ];
    }

    private function getBalanceSheetsForUpdate()
    {
        $data = $this->getBalanceSheets();
        $data['2021'] = [
            'totalAssets' => 200,
            'totalCurrentAssets' => 20,
            'totalCurrentLiabilities' => 20,
            'totalEquity' => 20,
            'longTermDebt' => 20,
            'currentPortionOfLongTermDebt' => 20,
            'shortTermInvestments' => 20,
            'currentCash' => 20,
            'cash' => 20,
            'tradeaccountsReceivableCurrent' => 20,
            'netIntangibleAssets' => 20,
            'tangibleAssets' => 20,
            'totalLiabalities' => 20
        ];

        return $data;
    }

    private function getCashFlows()
    {
        return [
            '2020'  => [
                'operatingCashFlow' => 50,
                'netIncome' => 10,
                'depreciationAmortization' => 10,
                'cashDividendsPaid' => 10,
                'capex' => 10,
                'cashFromInvesting' => 10,
                'cashFromFinancing' => 10,
                'freeCashFlow' => 10,
            ]
        ];
    }

    private function getCashFlowsForUpdate()
    {
        $data = $this->getCashFlows();
        $data['2021'] = [
            'operatingCashFlow' => 100,
            'netIncome' => 20,
            'depreciationAmortization' => 20,
            'cashDividendsPaid' => 20,
            'capex' => 20,
            'cashFromInvesting' => 20,
            'cashFromFinancing' => 20,
            'freeCashFlow' => 20,
        ];

        return $data;
    }

    private function getLastEvent(): array
    {
        /** @var RedisService */
        $redis = resolve(RedisService::class);
        $lastEvents = $redis->getLastEvents();

        return $lastEvents[count($lastEvents) - 1];
    }

    private function mockRedisService(string $fn)
    {
        $this->mock(RedisService::class, function (MockInterface $mock) use ($fn) {
            $mock->shouldReceive($fn);
        });
    }
}
