<?php

namespace Tests\Feature\Events;

use App\Events\Company\UpsertCompany;
use App\Exceptions\InvalidCompanyException;
use App\Models\Company\Company;
use App\Models\Company\Sector;
use App\Services\RedisService;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

class CreateCompanyTest extends TestCase
{
    use RefreshDatabase;

     /**
     * @test
     * @dataProvider invalidDataProvider()
     */
    public function it_should_throw_exception_if_financial_statement_is_invalid(array $incomeStatements, array $balanceSheets, array $cashFlows)
    {
        try {
            $this->mockRedisService('publishCompanyCreateFailed');
            event(new UpsertCompany($this->createUpserCompanyContainer([
                'ticker'    => 'TST',
                'name'      => 'Test Inc.',
                'sector'    => 'IT',
                'financialStatements'    => [
                    'incomeStatements'  => $incomeStatements,
                    'balanceSheets'     => $balanceSheets,
                    'cashFlows'         => $cashFlows
                ]
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    /** @test */
    public function it_should_create_a_company()
    {
        // $this->mockRedisService('publishCompanyCreated');
        $sector = Sector::factory()->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('companies', [
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector_id' => $sector->id
        ]);
    }

    /** @test */
    public function it_should_publish_the_newly_created_company()
    {
        $sector = Sector::factory()->state(['name' => 'Tech'])->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        $this->assertEquals('TST', $event['data']['ticker']);
        $this->assertEquals('Test Inc.', $event['data']['name']);
        $this->assertEquals('Tech', $event['data']['sector']['name']);
    }

     /** @test */
    public function it_should_publish_the_newly_created_company_with_financials()
    {
        $sector = Sector::factory()->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        $company = CompanyUpsertedContainer::from($event['data']);

        $this->assertEquals(110, $company->getIncomeStatements()->getRawValue('totalRevenue', 2020));
        $this->assertEquals(100, $company->getBalanceSheets()->getRawValue('totalAssets', 2020));
        $this->assertEquals(50, $company->getCashFlows()->getRawValue('operatingCashFlow', 2020));
    }

    /** @test */
    public function it_should_create_a_company_with_peers()
    {
        $sector = Sector::factory()->create();
        Company::factory()
            ->state([
                'ticker'    => 'TST1'
            ])
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'peers'     => ['TST1'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();

        $this->assertCount(1, $event['data']['peers']);
        $this->assertEquals('TST1', $event['data']['peers'][0]);
    }

     /** @test */
     public function it_should_create_a_company_with_only_existing_peers()
     {
        $sector = Sector::factory()->create();
        Company::factory()
            ->state([
                'ticker'    => 'TST1'
            ])
            ->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'sector'    => $sector->name,
            'peers'     => ['TST1', 'TST2', 'TST3'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();

        $this->assertCount(1, $event['data']['peers']);
        $this->assertEquals('TST1', $event['data']['peers'][0]);
     }

    /** @test */
    public function it_should_store_income_statements_for_a_company()
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
        $this->assertDatabaseHas('income_statements', [
            'ticker' => 'TST',
            'company_id' => $event['data']['id'],
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
    public function it_should_store_balance_sheets_for_a_company()
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
    public function it_should_store_cash_flows_for_a_company()
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
    public function it_should_rollback_database_if_error_happens()
    {
        /**
         * Ha financial statementek hozzáadásakor bármilyen hiba történik (pl egy számolásban), nem menti el a céget
         */
        try {
            $this->mockRedisService('publishCompanyCreateFailed');
            // $this->mock(ValueCalculatorService::class, function (MockInterface $mock) {
            //     $mock->shouldReceive('ratio')
            //         ->andThrow(new Exception('Error while storing company'));
            // });

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
        } catch (Exception) {
            $this->assertDatabaseCount('companies', 0);
        }
    }

    /** @test */
    public function it_should_publish_company_with_financials()
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
        $company = CompanyUpsertedContainer::from($event['data']);

        $this->assertNotEmpty($company->getIncomeStatements());
        $this->assertNotEmpty($company->getBalanceSheets());
        $this->assertNotEmpty($company->getCashFlows());
    }

    /** @test */
    public function it_should_not_create_a_peer_with_itself()
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
            'peers'     => ['TST'],
            'financialStatements'    => [
                'incomeStatements'  => $this->getIncomeStatements(),
                'balanceSheets'     => $this->getBalanceSheets(),
                'cashFlows'         => $this->getCashFlows()
            ]
        ]), false, 'abc-123'));

        $this->assertDatabaseCount('company_peers', 0);
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
                'costOfRevenue' => 10,
                'grossProfit' => 10,
                'operatingIncome' => 10,
                'pretaxIncome' => 10,
                'incomeTax' => 10,
                'interestExpense' => 10,
                'researchAndDevelopment' => 10,
                'sga' => 10,
                'netIncome' => 100,
                'ebit' => 10,
                'eps' => 10,
            ]
        ];
    }

    private function getBalanceSheets()
    {
        return [
            '2020'  => [
                'cash' => 10,
                'currentCash' => 10,
                'totalCurrentAssets' => 10,
                'netIntangibleAssets' => 10,
                'tangibleAssets' => 10,
                'shortTermInvestments' => 10,
                'tradeaccountsReceivableCurrent' => 10,
                'totalAssets' => 100,
                'totalEquity' => 10,
                'currentPortionOfLongTermDebt' => 10,
                'totalCurrentLiabilities' => 10,
                'longTermDebt' => 10,
                'totalLiabalities' => 10,
            ]
        ];
    }

    private function getCashFlows()
    {
        return [
            '2020'  => [
                'netIncome' => 10,
                'operatingCashFlow' => 50,
                'capex' => 10,
                'cashDividendsPaid' => 10,
                'depreciationAmortization' => 10,
                'freeCashFlow' => 10,
                'cashFromFinancing' => 10,
                'cashFromInvesting' => 10,
            ]
        ];
    }

    private function mockRedisService(string $fn)
    {
        $this->mock(RedisService::class, function (MockInterface $mock) use ($fn) {
            $mock->shouldReceive($fn);
        });
    }

    private function getLastEvent(): array
    {
        /** @var RedisService */
        $redis = resolve(RedisService::class);
        $lastEvents = $redis->getLastEvents();

        return $lastEvents[count($lastEvents) - 1];
    }
}
