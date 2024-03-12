<?php


namespace Tests\Feature\Services\FinancialStatement;


use App\Models\Company\Company;
use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\IncomeStatement;
use App\Services\FinancialStatement\FinancialStatementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement\IncomeStatementContainer;
use Tests\TestCase;

class FinancialStatementServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var FinancialStatementService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = resolve(FinancialStatementService::class);
    }

    /** @test */
    public function it_can_return_an_aggregated_collection_of_all_financial_items_for_a_company()
    {
        $company = Company::factory()
            ->state(['ticker' => 'TEST'])
            ->create();

        IncomeStatement::factory()
            ->state([
                'ticker' => 'TEST',
                'company_id' => $company->id,
                'year' => 2020,
                'total_revenue' => 1000
            ])
            ->create();

        BalanceSheet::factory()
            ->state([
                'ticker' => 'TEST',
                'company_id' => $company->id,
                'year' => 2020,
                'total_assets' => 10000
            ])
            ->create();

        CashFlow::factory()
            ->state([
                'ticker' => 'TEST',
                'company_id' => $company->id,
                'year' => 2020,
                'operating_cash_flow' => 1300
            ])
            ->create();

        $items = $this->service->getAvailableItems($company);

        $this->assertEquals('income_statements', $items['total_revenue']);
        $this->assertEquals('balance_sheets', $items['total_assets']);
        $this->assertEquals('cash_flows', $items['operating_cash_flow']);
    }

    /** @test */
    public function it_returns_a_summary_for_a_company()
    {
        $company = Company::factory()->state([
            'ticker'    => 'TEST',
            'name'      => 'Test Inc.'
        ])->create();

        IncomeStatement::factory()
            ->state([
                'ticker'                        => $company->ticker,
                'company_id'                    => $company->id,
                'year'                          => 2016,
                'net_income' => 10432
            ])
            ->create();

        IncomeStatement::factory()
            ->state([
                'ticker'                        => $company->ticker,
                'company_id'                    => $company->id,
                'year'                          => 2017,
                'net_income' => -12500
            ])
            ->create();
        IncomeStatement::factory()
            ->state([
                'ticker'                        => $company->ticker,
                'company_id'                    => $company->id,
                'year'                          => 2018,
                'total_revenue' => 872
            ])
            ->create();

        /** @var IncomeStatementContainer $container */
        $container = $this->service->getSummary($company, new IncomeStatement());

        $this->assertEquals(10432.0, $container->getRawValue('netIncome', 2016));
        $this->assertEquals('10.43B', $container->getFormattedValue('netIncome', 2016));
        $this->assertEquals([], $container->getClasses('netIncome', 2016));

        $this->assertEquals(-12500.0, $container->getRawValue('netIncome', 2017));
        $this->assertEquals('(12.50B)', $container->getFormattedValue('netIncome', 2017));
        $this->assertEquals(['negative'], $container->getClasses('netIncome', 2017));

        $this->assertEquals(872.0, $container->getRawValue('totalRevenue', 2018));
        $this->assertEquals('872.00M', $container->getFormattedValue('totalRevenue', 2018));
        $this->assertEquals([], $container->getClasses('totalRevenue', 2018));
    }

    /** @test */
    public function it_should_display_expense_like_items_as_negatives_in_summary()
    {
        $company = Company::factory()->state([
            'ticker'    => 'TEST',
            'name'      => 'Test Inc.'
        ])->create();

        IncomeStatement::factory()
            ->state([
                'ticker'                        => $company->ticker,
                'company_id'                    => $company->id,
                'year'                          => 2016,
                'interest_expense' => -4500, // not inverted
                'income_tax' => 539, // inverted
            ])
            ->create();

        /** @var IncomeStatementContainer $container */
        $container = $this->service->getSummary($company, new IncomeStatement);

        $this->assertEquals(-4500, $container->getRawValue('interestExpense', 2016));
        $this->assertEquals('(4.50B)', $container->getFormattedValue('interestExpense', 2016));
        $this->assertEquals(['negative'], $container->getClasses('interestExpense', 2016));

        $this->assertEquals(539, $container->getRawValue('incomeTax', 2016));
        $this->assertEquals('(539.00M)', $container->getFormattedValue('incomeTax', 2016));
        $this->assertEquals(['negative'], $container->getClasses('incomeTax', 2016));
    }
}
