<?php

namespace Tests\Feature\Events;

use App\Events\Company\UpsertCompany;
use App\Models\Company\Company;
use App\Models\Company\Sector;
use App\Services\RedisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCompanyIntegrationTest extends TestCase
{
    use RefreshDatabase;

    const TICKER = 'TEST';
    const NAME = 'Test Inc.';

    const NEW_YEAR = 2020;
    const LAST_YEAR = 2019;

    // Múlt évre, 2019 -re vonatkozó adatok. Ezeket NEM SZABAD frissíteni
    const LAST_YEAR_ASSETS = 5000000;
    const LAST_YEAR_REVENUE = 4000000;
    const LAST_YEAR_CASH_FLOW = 3000000;

    // Új évre, 2020 -ra vonatkozó adatok. Tehát ezeket kell hozzáadni
    const NEW_YEAR_ASSETS = 5000;
    const NEW_YEAR_REVENUE = 4000;
    const NEW_YEAR_CASH_FLOW = 3000;

    /** @test */
    public function it_can_create_and_update_a_company()
    {
        $company = $this->createCompany();

        $this->assertEquals([2019, 2018, 2017, 2016], $company->income_statements->pluck('year')->all());
        $this->assertEquals([2019, 2018, 2017, 2016], $company->balance_sheets->pluck('year')->all());
        $this->assertEquals([2019, 2018, 2017, 2016], $company->cash_flows->pluck('year')->all());

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'        => $company->ticker,
            'name'          => $company->name,
            'sector'        => $company->sector->name,
            'financialStatements'   => [
                'incomeStatements'  => $this->incomeStatementsForUpdate(),
                'balanceSheets'     => $this->balanceSheetsForUpdate(),
                'cashFlows'         => $this->cashFlowsForUpdate()
            ]
        ]), true, 'abc-123'));

        $event = $this->getLastEvent();
        $company = Company::find($event['data']['id']);

        $this->assertEquals([2019, 2018, 2017, 2016, 2020], $company->income_statements->pluck('year')->all());
        $this->assertEquals([2019, 2018, 2017, 2016, 2020], $company->balance_sheets->pluck('year')->all());
        $this->assertEquals([2019, 2018, 2017, 2016, 2020], $company->cash_flows->pluck('year')->all());

        // Bekerültek az újévi adatok
        $this->assertEquals(static::NEW_YEAR_REVENUE, $company->income_statements->where('year', static::NEW_YEAR)->first()->total_revenue);
        $this->assertEquals(static::NEW_YEAR_ASSETS, $company->balance_sheets->where('year', static::NEW_YEAR)->first()->total_assets);
        $this->assertEquals(static::NEW_YEAR_CASH_FLOW, $company->cash_flows->where('year', static::NEW_YEAR)->first()->operating_cash_flow);

        // Megmaradtak a tavalyi adatok
        $this->assertEquals(static::LAST_YEAR_REVENUE, $company->income_statements->where('year', static::LAST_YEAR)->first()->total_revenue);
        $this->assertEquals(static::LAST_YEAR_ASSETS, $company->balance_sheets->where('year', static::LAST_YEAR)->first()->total_assets);
        $this->assertEquals(static::LAST_YEAR_CASH_FLOW, $company->cash_flows->where('year', static::LAST_YEAR)->first()->operating_cash_flow);
    }

    // -------- Helpers --------

    protected function balanceSheets()
    {
        return [
            '2019'  => [
                'totalAssets'  => static::LAST_YEAR_ASSETS,
                'cash' => 1000,
                'currentCash' => 1000,
                'totalCurrentAssets' => 1000,
                'netIntangibleAssets' => 1000,
                'tangibleAssets' => 1000,
                'shortTermInvestments' => 1000,
                'tradeaccountsReceivableCurrent' => 1000,
                'totalEquity' => 1000,
                'currentPortionOfLongTermDebt' => 1000,
                'totalCurrentLiabilities' => 1000,
                'longTermDebt' => 1000,
                'totalLiabalities' => 1000,
            ],
            '2018'  => [
                'totalAssets'  => 2018,
                'cash' => 1000,
                'currentCash' => 1000,
                'totalCurrentAssets' => 1000,
                'netIntangibleAssets' => 1000,
                'tangibleAssets' => 1000,
                'shortTermInvestments' => 1000,
                'tradeaccountsReceivableCurrent' => 1000,
                'totalEquity' => 1000,
                'currentPortionOfLongTermDebt' => 1000,
                'totalCurrentLiabilities' => 1000,
                'longTermDebt' => 1000,
                'totalLiabalities' => 1000,
            ],
            '2017'  => [
                'totalAssets'  => 2017,
                'cash' => 1000,
                'currentCash' => 1000,
                'totalCurrentAssets' => 1000,
                'netIntangibleAssets' => 1000,
                'tangibleAssets' => 1000,
                'shortTermInvestments' => 1000,
                'tradeaccountsReceivableCurrent' => 1000,
                'totalEquity' => 1000,
                'currentPortionOfLongTermDebt' => 1000,
                'totalCurrentLiabilities' => 1000,
                'longTermDebt' => 1000,
                'totalLiabalities' => 1000,
            ],
            '2016'  => [
                'totalAssets'  => 2016,
                'cash' => 1000,
                'currentCash' => 1000,
                'totalCurrentAssets' => 1000,
                'netIntangibleAssets' => 1000,
                'tangibleAssets' => 1000,
                'shortTermInvestments' => 1000,
                'tradeaccountsReceivableCurrent' => 1000,
                'totalEquity' => 1000,
                'currentPortionOfLongTermDebt' => 1000,
                'totalCurrentLiabilities' => 1000,
                'longTermDebt' => 1000,
                'totalLiabalities' => 1000,
            ],
        ];
    }

    protected function balanceSheetsForUpdate()
    {
        $data = $this->balanceSheets();
        $data[static::NEW_YEAR] = [
            'totalAssets'      => static::NEW_YEAR_ASSETS,
            'cash' => 1000,
            'currentCash' => 1000,
            'totalCurrentAssets' => 1000,
            'netIntangibleAssets' => 1000,
            'tangibleAssets' => 1000,
            'shortTermInvestments' => 1000,
            'tradeaccountsReceivableCurrent' => 1000,
            'totalEquity' => 1000,
            'currentPortionOfLongTermDebt' => 1000,
            'totalCurrentLiabilities' => 1000,
            'longTermDebt' => 1000,
            'totalLiabalities' => 1000,
        ];
        $data[static::LAST_YEAR]['totalAssets'] = 87654;

        return $data;
    }

    protected function incomeStatements()
    {
        return [
            '2019'  => [
                'totalRevenue'     => static::LAST_YEAR_REVENUE,
                'netIncome'        => 100,
                'costOfRevenue' => 1000,
                'grossProfit' => 1000,
                'operatingIncome' => 1000,
                'pretaxIncome' => 1000,
                'incomeTax' => 1000,
                'interestExpense' => 1000,
                'researchAndDevelopment' => 1000,
                'sga' => 1000,
                'ebit' => 1000,
                'eps' => 1000,
            ],
            '2018'  => [
                'totalRevenue'     => 2018,
                'netIncome'        => 100,
                'costOfRevenue' => 1000,
                'grossProfit' => 1000,
                'operatingIncome' => 1000,
                'pretaxIncome' => 1000,
                'incomeTax' => 1000,
                'interestExpense' => 1000,
                'researchAndDevelopment' => 1000,
                'sga' => 1000,
                'ebit' => 1000,
                'eps' => 1000,

            ],
            '2017'  => [
                'totalRevenue'     => 2017,
                'netIncome'        => 100,
                'costOfRevenue' => 1000,
                'grossProfit' => 1000,
                'operatingIncome' => 1000,
                'pretaxIncome' => 1000,
                'incomeTax' => 1000,
                'interestExpense' => 1000,
                'researchAndDevelopment' => 1000,
                'sga' => 1000,
                'ebit' => 1000,
                'eps' => 1000,
            ],
            '2016'  => [
                'totalRevenue'     => 2016,
                'netIncome'        => 100,
                'costOfRevenue' => 1000,
                'grossProfit' => 1000,
                'operatingIncome' => 1000,
                'pretaxIncome' => 1000,
                'incomeTax' => 1000,
                'interestExpense' => 1000,
                'researchAndDevelopment' => 1000,
                'sga' => 1000,
                'ebit' => 1000,
                'eps' => 1000,
            ]
        ];
    }

    protected function incomeStatementsForUpdate()
    {
        $data = $this->incomeStatements();
        $data[static::NEW_YEAR] = [
            'totalRevenue'     => static::NEW_YEAR_REVENUE,
            'netIncome'        => 100,
            'costOfRevenue' => 1000,
            'grossProfit' => 1000,
            'operatingIncome' => 1000,
            'pretaxIncome' => 1000,
            'incomeTax' => 1000,
            'interestExpense' => 1000,
            'researchAndDevelopment' => 1000,
            'sga' => 1000,
            'ebit' => 1000,
            'eps' => 1000,
        ];

        $data[static::LAST_YEAR]['totalRevenue'] = 8765;
        return $data;
    }

    protected function cashFlows()
    {
        return [
            '2019'  => [
                'operatingCashFlow'   => static::LAST_YEAR_CASH_FLOW,
                'netIncome' => 1000,
                'capex' => 1000,
                'cashDividendsPaid' => 1000,
                'depreciationAmortization' => 1000,
                'freeCashFlow' => 1000,
                'cashFromFinancing' => 1000,
                'cashFromInvesting' => 1000,
            ],
            '2018'  => [
                'operatingCashFlow'   => 2018,
                'netIncome' => 1000,
                'capex' => 1000,
                'cashDividendsPaid' => 1000,
                'depreciationAmortization' => 1000,
                'freeCashFlow' => 1000,
                'cashFromFinancing' => 1000,
                'cashFromInvesting' => 1000,
            ],
            '2017'  => [
                'operatingCashFlow'   => 2017,
                'netIncome' => 1000,
                'capex' => 1000,
                'cashDividendsPaid' => 1000,
                'depreciationAmortization' => 1000,
                'freeCashFlow' => 1000,
                'cashFromFinancing' => 1000,
                'cashFromInvesting' => 1000,
            ],
            '2016'  => [
                'operatingCashFlow'   => 2016,
                'netIncome' => 1000,
                'capex' => 1000,
                'cashDividendsPaid' => 1000,
                'depreciationAmortization' => 1000,
                'freeCashFlow' => 1000,
                'cashFromFinancing' => 1000,
                'cashFromInvesting' => 1000,
            ]
        ];
    }

    protected function cashFlowsForUpdate()
    {
        $data = $this->cashFlows();
        $data[static::NEW_YEAR] = [
            'operatingCashFlow'   => static::NEW_YEAR_CASH_FLOW,
            'netIncome' => 1000,
            'capex' => 1000,
            'cashDividendsPaid' => 1000,
            'depreciationAmortization' => 1000,
            'freeCashFlow' => 1000,
            'cashFromFinancing' => 1000,
            'cashFromInvesting' => 1000,
        ];

        $data[static::LAST_YEAR]['operatingCashFlow'] = 8765;
        return $data;
    }

    protected function createCompany(): Company
    {
        $sector = Sector::factory()->create();

        event(new UpsertCompany($this->createUpserCompanyContainer([
            'ticker'        => static::TICKER,
            'name'          => static::NAME,
            'sector'        => $sector->name,
            'financialStatements'   => [
                'incomeStatements'  => $this->incomeStatements(),
                'balanceSheets'     => $this->balanceSheets(),
                'cashFlows'         => $this->cashFlows()
            ]
        ]), false, 'abc-123'));

        $event = $this->getLastEvent();
        return Company::find($event['data']['id']);
    }

    private function getLastEvent(): array
    {
        /** @var RedisService */
        $redis = resolve(RedisService::class);
        $lastEvents = $redis->getLastEvents();

        return $lastEvents[count($lastEvents) - 1];
    }
}
