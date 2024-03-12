<?php


namespace Tests\Unit\Models\FinancialStatement\Factory;


use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\Factory\FinancialStatementFactory;
use App\Models\FinancialStatement\IncomeStatement;
use App\Services\FinancialStatement\FinancialStatementService;
use Tests\TestCase;

class FinancialStatementFactoryTest extends TestCase
{
    /** @var FinancialStatementFactory */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = resolve(FinancialStatementFactory::class);
    }

    /** @test */
    public function it_can_create_an_income_statement()
    {
        $statement = $this->factory->create(FinancialStatementService::TYPE_INCOME_STATEMENT);
        $this->assertInstanceOf(IncomeStatement::class, $statement);
    }

    /** @test */
    public function it_can_create_a_balance_sheet()
    {
        $statement = $this->factory->create(FinancialStatementService::TYPE_BALANCE_SHEET);
        $this->assertInstanceOf(BalanceSheet::class, $statement);
    }

    /** @test */
    public function it_can_create_a_cash_flow()
    {
        $statement = $this->factory->create(FinancialStatementService::TYPE_CASH_FLOW);
        $this->assertInstanceOf(CashFlow::class, $statement);
    }

    /** @test */
    public function it_throws_an_exception_if_invalid_type_given()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create('INVALID');
    }
}