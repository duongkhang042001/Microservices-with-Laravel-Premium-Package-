<?php


namespace Tests\Unit\Repositories\FinancialStatement\Factory;


use App\Repositories\FinancialStatement\BalanceSheetRepository;
use App\Repositories\FinancialStatement\CashFlowRepository;
use App\Repositories\FinancialStatement\Factory\FinancialStatementRepositoryFactory;
use App\Repositories\FinancialStatement\IncomeStatementRepository;
use Tests\TestCase;

class FinancialStatementRepositoryFactoryTest extends TestCase
{
    /** @var FinancialStatementRepositoryFactory */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = resolve(FinancialStatementRepositoryFactory::class);
    }

    /** @test */
    public function it_can_create_an_income_statement()
    {
        $statement = $this->factory->create('income_statements');
        $this->assertInstanceOf(IncomeStatementRepository::class, $statement);
    }

    /** @test */
    public function it_can_create_a_balance_sheet()
    {
        $statement = $this->factory->create('balance_sheets');
        $this->assertInstanceOf(BalanceSheetRepository::class, $statement);
    }

    /** @test */
    public function it_can_create_a_cash_flow()
    {
        $statement = $this->factory->create('cash_flows');
        $this->assertInstanceOf(CashFlowRepository::class, $statement);
    }

    /** @test */
    public function it_throws_an_exception_if_invalid_type_given()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create('INVALID');
    }
}