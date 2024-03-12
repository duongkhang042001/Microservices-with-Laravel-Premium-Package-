<?php

namespace Tests;

use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\CompanyMetricMedian;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createCompanyUpsertedContainer(array $data)
    {
        $data = [
            'id'        => 1,
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'fullName'  => 'TST - Test Inc.',
            'sector' => [
                'id'    => 1,
                'name'  => 'Tech'
            ],
            'financialStatements' => [
                'incomeStatements'  => $this->createIncomeStatement(Arr::get($data, 'incomeStatements', [])),
                'balanceSheets'     => $this->createBalancSheet(Arr::get($data, 'balanceSheets', [])),
                'cashFlows'         => $this->createCashFlow(Arr::get($data, 'cashFlows', [])),
            ]
        ];

        return CompanyUpsertedContainer::from($data);
    }

    protected function createIncomeStatement(array $data)
    {
        return [
            'totalRevenue' => $this->createItem('totalRevenue', Arr::get($data, 'totalRevenue', rand(100, 1000))),
            'costOfRevenue' => $this->createItem('costOfRevenue', Arr::get($data, 'costOfRevenue', rand(100, 1000))),
            'grossProfit' => $this->createItem('grossProfit', Arr::get($data, 'grossProfit', rand(100, 1000))),
            'operatingIncome' => $this->createItem('operatingIncome', Arr::get($data, 'operatingIncome', rand(100, 1000))),
            'pretaxIncome' => $this->createItem('pretaxIncome', Arr::get($data, 'pretaxIncome', rand(100, 1000))),
            'incomeTax' => $this->createItem('incomeTax', Arr::get($data, 'incomeTax', rand(100, 1000))),
            'interestExpense' => $this->createItem('interestExpense', Arr::get($data, 'interestExpense', rand(100, 1000))),
            'researchAndDevelopment' => $this->createItem('researchAndDevelopment', Arr::get($data, 'researchAndDevelopment', rand(100, 1000))),
            'sellingGeneralAdministrative' => $this->createItem('sellingGeneralAdministrative', Arr::get($data, 'sellingGeneralAdministrative', rand(100, 1000))),
            'netIncome' => $this->createItem('netIncome', Arr::get($data, 'netIncome', rand(100, 1000))),
            'ebit' => $this->createItem('ebit', Arr::get($data, 'ebit', rand(100, 1000))),
            'eps' => $this->createItem('eps', Arr::get($data, 'eps', rand(100, 1000))),
        ];
    }

    protected function createBalancSheet(array $data)
    {
        return [
            'cash' => $this->createItem('cash', Arr::get($data, 'cash', rand(100, 1000))),
            'currentCash' => $this->createItem('currentCash', Arr::get($data, 'currentCash', rand(100, 1000))),
            'totalCurrentAssets' => $this->createItem('totalCurrentAssets', Arr::get($data, 'totalCurrentAssets', rand(100, 1000))),
            'netIntangibleAssets' => $this->createItem('netIntangibleAssets', Arr::get($data, 'netIntangibleAssets', rand(100, 1000))),
            'tangibleAssets' => $this->createItem('tangibleAssets', Arr::get($data, 'tangibleAssets', rand(100, 1000))),
            'shortTermInvestments' => $this->createItem('shortTermInvestments', Arr::get($data, 'shortTermInvestments', rand(100, 1000))),
            'currentAccountAndReceivables' => $this->createItem('currentAccountAndReceivables', Arr::get($data, 'currentAccountAndReceivables', rand(100, 1000))),
            'totalAssets' => $this->createItem('totalAssets', Arr::get($data, 'totalAssets', rand(100, 1000))),
            'totalEquity' => $this->createItem('totalEquity', Arr::get($data, 'totalEquity', rand(100, 1000))),
            'currentPortionOfLongTermDebt' => $this->createItem('currentPortionOfLongTermDebt', Arr::get($data, 'currentPortionOfLongTermDebt', rand(100, 1000))),
            'totalCurrentLiabilities' => $this->createItem('totalCurrentLiabilities', Arr::get($data, 'totalCurrentLiabilities', rand(100, 1000))),
            'longTermDebt' => $this->createItem('longTermDebt', Arr::get($data, 'longTermDebt', rand(100, 1000))),
            'totalLiabilities' => $this->createItem('totalLiabilities', Arr::get($data, 'totalLiabilities', rand(100, 1000))),
        ];
    }

    protected function createCashFlow(array $data)
    {
        return [
            'netIncome' => $this->createItem('netIncome', Arr::get($data, 'netIncome', rand(100, 1000))),
            'operatingCashFlow' => $this->createItem('operatingCashFlow', Arr::get($data, 'operatingCashFlow', rand(100, 1000))),
            'capex' => $this->createItem('capex', Arr::get($data, 'capex', rand(100, 1000))),
            'cashDividendPaid' => $this->createItem('cashDividendPaid', Arr::get($data, 'cashDividendPaid', rand(100, 1000))),
            'deprecationAmortization' => $this->createItem('deprecationAmortization', Arr::get($data, 'deprecationAmortization', rand(100, 1000))),
            'freeCashFlow' => $this->createItem('freeCashFlow', Arr::get($data, 'freeCashFlow', rand(100, 1000))),
            'cashFromFinancing' => $this->createItem('cashFromFinancing', Arr::get($data, 'cashFromFinancing', rand(100, 1000))),
            'cashFromInvesting' => $this->createItem('cashFromInvesting', Arr::get($data, 'cashFromInvesting', rand(100, 1000))),
        ];
    }

    private function createItem(string $name, array | float $value)
    {
        $item = [
            'name' => $name,
            'formatter' => 'number',
            'isInverted' => false,
            'shouldHighlightNegativeValue' => false,
            'data' => []
        ];

        if (is_array($value)) {
            foreach ($value as $year => $rawValue) {
                $item['data'][$year] = [
                    'rawValue' => $rawValue,
                    'formattedValue' => $rawValue,
                ];
            }
        } else {
            $item['data'] = [
                2020 => [
                    'rawValue' => $value,
                    'formattedValue' => $value,
                ],
                2019 => [
                    'rawValue' => $value,
                    'formattedValue' => $value,
                ],
                2018 => [
                    'rawValue' => $value,
                    'formattedValue' => $value,
                ],
                2017 => [
                    'rawValue' => $value,
                    'formattedValue' => $value,
                ],
                2016 => [
                    'rawValue' => $value,
                    'formattedValue' => $value,
                ],
            ];
        }

        return $item;
    }

    protected function getCompanyMetric(int $year)
    {
        return CompanyMetric::where('ticker', 'TST')
            ->where('year', $year)
            ->first();
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function createCompanyMetric(array $state, ?Company $company = null)
    {
        if ($company) {
            $state['ticker'] = $company->ticker;
            $state['company_id'] = $company->id;
        }

        CompanyMetric::factory()
            ->state($state)
            ->create();

        CompanyMetricMedian::factory($state)->create();
    }

    // -------- Helpers --------
    protected function createCompanyMetricWithFixValue(
        string $ticker,
        array $overrides,
        Company $company = null
    ): CompanyMetric {
        $value = 1;
        $data = [
            'ticker' => $ticker,
            'debt_to_capital' => Arr::get($overrides, 'debt_to_capital', $value),
            'current_ratio' => Arr::get($overrides, 'current_ratio', $value),
            'quick_ratio' => Arr::get($overrides, 'quick_ratio', $value),
            'cash_to_debt' => Arr::get($overrides, 'cash_to_debt', $value),
            'interest_to_operating_profit' => Arr::get($overrides, 'interest_to_operating_profit', $value),
            'long_term_debt_to_ebitda' => Arr::get($overrides, 'long_term_debt_to_ebitda', $value),
            'interest_coverage_ratio' => Arr::get($overrides, 'interest_coverage_ratio', $value),
            'debt_to_assets' => Arr::get($overrides, 'debt_to_assets', $value),
            'operating_cash_flow_to_current_liabilities' => Arr::get($overrides, 'operating_cash_flow_to_current_liabilities', $value),
            'capex_as_percent_of_revenue' => Arr::get($overrides, 'capex_as_percent_of_revenue', $value),
            'capex_as_percent_of_operating_cash_flow' => Arr::get($overrides, 'capex_as_percent_of_operating_cash_flow', $value),
            'payout_ratio' => Arr::get($overrides, 'payout_ratio', $value),
            'roic' => Arr::get($overrides, 'roic', $value),
            'croic' => Arr::get($overrides, 'croic', $value),
            'rota' => Arr::get($overrides, 'rota', $value),
            'roa' => Arr::get($overrides, 'roa', $value),
            'roe' => Arr::get($overrides, 'roe', $value),
            'free_cash_flow_to_revenue' => Arr::get($overrides, 'free_cash_flow_to_revenue', $value),
            'net_margin' => Arr::get($overrides, 'net_margin', $value),
            'operating_margin' => Arr::get($overrides, 'operating_margin', $value),
            'gross_margin' => Arr::get($overrides, 'gross_margin', $value),
            'operating_cash_flow_margin' => Arr::get($overrides, 'operating_cash_flow_margin', $value),
            'sga_to_gross_profit' => Arr::get($overrides, 'sga_to_gross_profit', $value),
            'eps_growth' => Arr::get($overrides, 'eps_growth', $value),
            'net_income_growth' => Arr::get($overrides, 'net_income_growth', $value),
            'total_revenue_growth' => Arr::get($overrides, 'total_revenue_growth', $value),
        ];

        if ($company) {
            $data['company_id'] = $company->id;
        }

        $metric = CompanyMetric::factory($data)->create();
        CompanyMetricMedian::factory($data)->create();

        return $metric;
    }
}
