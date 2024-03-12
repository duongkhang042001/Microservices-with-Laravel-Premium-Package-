<?php

namespace Tests\Feature\Events;

use App\Events\CompanyUpserted;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\FinancialStatementsContainer;

class StoreCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_a_company()
    {
        $companyContainer = $this->createCompanyUpsertedContainer([
            'ticker'            => 'TST',
            'financials'        => [
                ['year'  => 2020, 'item'  => 'grossMargin', 'value' => 0.8, 'type'  => 'number', 'name'  => 'Gross Margin'],
                ['year'  => 2020, 'item'  => 'operatingMargin', 'value' => 0.5, 'type'  => 'number', 'name'  => 'Operating Margin'],
                ['year'  => 2020, 'item'  => 'netMargin', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'capexInverted', 'value' => 10, 'type'  => 'number', 'name'  => 'capexInverted'],
                ['year'  => 2020, 'item'  => 'fcfe', 'value' => 90, 'type'  => 'number', 'name'  => 'fcfe'],

                ['year'  => 2020, 'item'  => 'revenueGrowth', 'value' => 0.3, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2019, 'item'  => 'revenueGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2020, 'item'  => 'netIncomeGrowth', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2019, 'item'  => 'netIncomeGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2020, 'item'  => 'epsGrowth', 'value' => 0.1, 'type'  => 'number', 'name'  => 'EPS Growth'],
                ['year'  => 2019, 'item'  => 'epsGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'EPS Growth'],
            ],
            'incomeStatements'  => [
                ['year'  => 2020, 'item'  => 'netIncome', 'value' => 133, 'type'  => 'number', 'name'  => 'Net Income'],
                ['year'  => 2020, 'item'  => 'totalRevenue', 'value' => 133, 'type'  => 'number', 'name'  => 'Total Revenue'],
                ['year'  => 2020, 'item'  => 'grossProfit', 'value' => 13.30, 'type'  => 'number', 'name'  => 'Gross Profit'],
                ['year'  => 2020, 'item'  => 'operatingIncome', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Operating Income'],
                ['year'  => 2017, 'item'  => 'eps', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'balanceSheets'     => [
                ['year'  => 2020, 'item'  => 'cash', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'longTermDebt', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalEquity', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentAssets', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentLiabilities', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'cashFlows'         => [
                ['year'  => 2020, 'item'  => 'operatingCashFlow', 'value' => 100, 'type'  => 'number', 'name'  => 'Cash from Operating Activities'],
            ],
        ]);

        event(new CompanyUpserted($companyContainer));

        $this->assertDatabaseHas('companies', [
            'ticker' => 'TST'
        ]);
    }

    /** @test */
    public function it_should_store_income_statements_for_company()
    {
        $companyContainer = $this->createCompanyUpsertedContainer([
            'ticker'            => 'TST',
            'financials'        => [
                ['year'  => 2020, 'item'  => 'grossMargin', 'value' => 0.8, 'type'  => 'number', 'name'  => 'Gross Margin'],
                ['year'  => 2020, 'item'  => 'operatingMargin', 'value' => 0.5, 'type'  => 'number', 'name'  => 'Operating Margin'],
                ['year'  => 2020, 'item'  => 'netMargin', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'capexInverted', 'value' => 10, 'type'  => 'number', 'name'  => 'capexInverted'],
                ['year'  => 2020, 'item'  => 'fcfe', 'value' => 90, 'type'  => 'number', 'name'  => 'fcfe'],

                ['year'  => 2020, 'item'  => 'revenueGrowth', 'value' => 0.3, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2019, 'item'  => 'revenueGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2020, 'item'  => 'netIncomeGrowth', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2019, 'item'  => 'netIncomeGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2020, 'item'  => 'epsGrowth', 'value' => 0.1, 'type'  => 'number', 'name'  => 'EPS Growth'],
                ['year'  => 2019, 'item'  => 'epsGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'EPS Growth'],
            ],
            'incomeStatements'  => [
                ['year'  => 2020, 'item'  => 'netIncome', 'value' => 133, 'type'  => 'number', 'name'  => 'Net Income'],
                ['year'  => 2020, 'item'  => 'totalRevenue', 'value' => 100, 'type'  => 'number', 'name'  => 'Total Revenue'],
                ['year'  => 2020, 'item'  => 'grossProfit', 'value' => 13.30, 'type'  => 'number', 'name'  => 'Gross Profit'],
                ['year'  => 2020, 'item'  => 'operatingIncome', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Operating Income'],
                ['year'  => 2017, 'item'  => 'eps', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'balanceSheets'     => [
                ['year'  => 2020, 'item'  => 'cash', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'longTermDebt', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalEquity', 'value' => 50, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentAssets', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentLiabilities', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'cashFlows'         => [
                ['year'  => 2020, 'item'  => 'operatingCashFlow', 'value' => 20, 'type'  => 'number', 'name'  => 'Cash from Operating Activities'],
            ],
        ]);

        event(new CompanyUpserted($companyContainer));

        $company = Company::first();

        $this->assertEquals(
            100,
            $company->financial_statements['incomeStatements']['totalRevenue']['data']['2020']['rawValue']
        );
    }

    /** @test */
    public function it_should_store_balance_sheets_for_company()
    {
        $companyContainer = $this->createCompanyUpsertedContainer([
            'ticker'            => 'TST',
            'financials'        => [
                ['year'  => 2020, 'item'  => 'grossMargin', 'value' => 0.8, 'type'  => 'number', 'name'  => 'Gross Margin'],
                ['year'  => 2020, 'item'  => 'operatingMargin', 'value' => 0.5, 'type'  => 'number', 'name'  => 'Operating Margin'],
                ['year'  => 2020, 'item'  => 'netMargin', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'capexInverted', 'value' => 10, 'type'  => 'number', 'name'  => 'capexInverted'],
                ['year'  => 2020, 'item'  => 'fcfe', 'value' => 90, 'type'  => 'number', 'name'  => 'fcfe'],

                ['year'  => 2020, 'item'  => 'revenueGrowth', 'value' => 0.3, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2019, 'item'  => 'revenueGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2020, 'item'  => 'netIncomeGrowth', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2019, 'item'  => 'netIncomeGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2020, 'item'  => 'epsGrowth', 'value' => 0.1, 'type'  => 'number', 'name'  => 'EPS Growth'],
                ['year'  => 2019, 'item'  => 'epsGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'EPS Growth'],
            ],
            'incomeStatements'  => [
                ['year'  => 2020, 'item'  => 'netIncome', 'value' => 133, 'type'  => 'number', 'name'  => 'Net Income'],
                ['year'  => 2020, 'item'  => 'totalRevenue', 'value' => 100, 'type'  => 'number', 'name'  => 'Total Revenue'],
                ['year'  => 2020, 'item'  => 'grossProfit', 'value' => 13.30, 'type'  => 'number', 'name'  => 'Gross Profit'],
                ['year'  => 2020, 'item'  => 'operatingIncome', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Operating Income'],
                ['year'  => 2017, 'item'  => 'eps', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'balanceSheets'     => [
                ['year'  => 2020, 'item'  => 'cash', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'longTermDebt', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalEquity', 'value' => 50, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentAssets', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentLiabilities', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'cashFlows'         => [
                ['year'  => 2020, 'item'  => 'operatingCashFlow', 'value' => 20, 'type'  => 'number', 'name'  => 'Cash from Operating Activities'],
            ],
        ]);

        event(new CompanyUpserted($companyContainer));

        $company = Company::first();

        $this->assertEquals(
            50,
            $company->financial_statements['balanceSheets']['totalEquity']['data']['2020']['rawValue']
        );
    }

    /** @test */
    public function it_should_store_cash_flows_for_company()
    {
        $companyContainer = $this->createCompanyUpsertedContainer([
            'ticker'            => 'TST',
            'financials'        => [
                ['year'  => 2020, 'item'  => 'grossMargin', 'value' => 0.8, 'type'  => 'number', 'name'  => 'Gross Margin'],
                ['year'  => 2020, 'item'  => 'operatingMargin', 'value' => 0.5, 'type'  => 'number', 'name'  => 'Operating Margin'],
                ['year'  => 2020, 'item'  => 'netMargin', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'capexInverted', 'value' => 10, 'type'  => 'number', 'name'  => 'capexInverted'],
                ['year'  => 2020, 'item'  => 'fcfe', 'value' => 90, 'type'  => 'number', 'name'  => 'fcfe'],

                ['year'  => 2020, 'item'  => 'revenueGrowth', 'value' => 0.3, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2019, 'item'  => 'revenueGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Revenue Growth'],
                ['year'  => 2020, 'item'  => 'netIncomeGrowth', 'value' => 0.2, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2019, 'item'  => 'netIncomeGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'Net Income Growth'],
                ['year'  => 2020, 'item'  => 'epsGrowth', 'value' => 0.1, 'type'  => 'number', 'name'  => 'EPS Growth'],
                ['year'  => 2019, 'item'  => 'epsGrowth', 'value' => null, 'type'  => 'number', 'name'  => 'EPS Growth'],
            ],
            'incomeStatements'  => [
                ['year'  => 2020, 'item'  => 'netIncome', 'value' => 133, 'type'  => 'number', 'name'  => 'Net Income'],
                ['year'  => 2020, 'item'  => 'totalRevenue', 'value' => 100, 'type'  => 'number', 'name'  => 'Total Revenue'],
                ['year'  => 2020, 'item'  => 'grossProfit', 'value' => 13.30, 'type'  => 'number', 'name'  => 'Gross Profit'],
                ['year'  => 2020, 'item'  => 'operatingIncome', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Operating Income'],
                ['year'  => 2017, 'item'  => 'eps', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'balanceSheets'     => [
                ['year'  => 2020, 'item'  => 'cash', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'longTermDebt', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalEquity', 'value' => 50, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentAssets', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
                ['year'  => 2020, 'item'  => 'totalCurrentLiabilities', 'value' => 1.33, 'type'  => 'number', 'name'  => 'Net Margin'],
            ],
            'cashFlows'         => [
                ['year'  => 2020, 'item'  => 'operatingCashFlow', 'value' => 20, 'type'  => 'number', 'name'  => 'Cash from Operating Activities'],
            ],
        ]);

        event(new CompanyUpserted($companyContainer));

        $company = Company::first();

        $this->assertEquals(
            20,
            $company->financial_statements['cashFlows']['operatingCashFlow']['data']['2020']['rawValue']
        );
    }
}
