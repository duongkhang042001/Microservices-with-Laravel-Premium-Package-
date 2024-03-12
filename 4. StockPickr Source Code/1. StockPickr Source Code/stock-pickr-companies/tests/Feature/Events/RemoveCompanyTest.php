<?php

namespace Tests\Feature\Events;

use App\Events\Company\RemoveCompany;
use App\Models\Company\Company;
use App\Models\Company\CompanyPeer;
use App\Models\FinancialStatement\BalanceSheet;
use App\Models\FinancialStatement\CashFlow;
use App\Models\FinancialStatement\IncomeStatement;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_remove_a_company()
    {
        Company::factory(['ticker' => 'TST'])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('companies', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_income_statements()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        IncomeStatement::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019
        ])->create();
        IncomeStatement::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020
        ])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('income_statements', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_balance_sheets()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        BalanceSheet::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019
        ])->create();
        BalanceSheet::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020
        ])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('balance_sheets', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_cash_flwos()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        CashFlow::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2019
        ])->create();
        CashFlow::factory([
            'ticker' => 'TST',
            'company_id' => $company->id,
            'year' => 2020
        ])->create();

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('cash_flows', 0);
    }

    /** @test */
    public function it_should_remove_a_company_with_peers()
    {
        $company = Company::factory(['ticker' => 'TST'])->create();
        $peer1 = Company::factory(['ticker' => 'PEE1'])->create();
        $peer2 = Company::factory(['ticker' => 'PEE2'])->create();

        CompanyPeer::factory([
            'company_id' => $company->id,
            'peer_id' => $peer1->id,
        ]);
        CompanyPeer::factory([
            'company_id' => $company->id,
            'peer_id' => $peer2->id,
        ]);

        event(new RemoveCompany('TST'));

        $this->assertDatabaseCount('company_peers', 0);
    }
}
