<?php

namespace Tests;

use App\ChartConfigs\ChartConfig;
use App\ChartConfigs\ChartFactory;
use App\Models\Chart;
use Exception;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function tearDown(): void
    {
        Redis::flushall();
        parent::tearDown();
    }

    public function createAllCharts($ticker)
    {
        $chartFactory = resolve(ChartFactory::class);

        /** @var ChartConfig $chart */
        foreach ($chartFactory->createGroup('all') as $chart) {
            Chart::factory()
                ->state([
                    'ticker'    => $ticker,
                    'chart'     => $chart->slug(),
                    'data'      => [
                        [
                            'label' => 'Label',
                            'data'  => [
                                133, 121, 110, 100
                            ],
                            'fill'  => false
                        ],
                    ]
                ])->create();
        }

    }

    protected function createCompanyUpsertedContainer(array $data)
    {
        $data = array_merge([
            'id'        => 1,
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'fullName'  => 'TST - Test Inc.',
            'sector' => [
                'id'    => 1,
                'name'  => 'Tech'
            ],
            'financialStatements' => [
                'incomeStatements'  => $this->restructureFinancialStatement(Arr::get($data, 'incomeStatements', []), 'incomeStatements'),
                'balanceSheets'     => $this->restructureFinancialStatement(Arr::get($data, 'balanceSheets', []), 'balanceSheets'),
                'cashFlows'         => $this->restructureFinancialStatement(Arr::get($data, 'cashFlows', []), 'cashFlows'),
            ]
        ], $data);

        return CompanyUpsertedContainer::from($data);
    }

    private function restructureFinancialStatement(array $financialStatement, string $type): array
    {
        $financialStatement = match ($type) {
            'incomeStatements' => $this->addMissingIncomeStatementItems($financialStatement),
            'balanceSheets' => $this->addMissingBalanceSheetItems($financialStatement),
            'cashFlows' => $this->addMissingCashFlowItems($financialStatement),
            default => new Exception('No financial statement found for type: ' . $type)
        };

        /**
         * "totalRevenue": {
         *   "name": "Total Revenue",
         *   ...
         *   "data": {
         *     "2020" => {
         *        "rawValue": 100,
         *        ...
         *     }
         *   }
         * }
         */
        $result = [];
        foreach ($financialStatement as $item) {

            $data = [];
            foreach ($financialStatement as $statementItem) {
                if ($statementItem['item'] === $item['item']) {
                    data_fill($data, $statementItem['year'], [
                        'rawValue' => $statementItem['value'],
                        'formattedValue' => $statementItem['value']
                    ]);
                }
            }

            $result[$item['item']] = [
                'name' => $item['item'],
                'formatter' => 'financialNumber',
                'shouldHighlightNegativeValue' => false,
                'isInverted' => false,
                'data' => $data
            ];
        }

        return $result;
    }

    private function addMissingIncomeStatementItems(array $incomeStatement)
    {
        $newIncomeStatement = $incomeStatement;
        $items = ['costOfRevenue', 'pretaxIncome', 'incomeTax', 'interestExpense', 'researchAndDevelopment', 'sellingGeneralAdministrative', 'ebit'];
        $years = collect($incomeStatement)
            ->pluck('year')
            ->unique()
            ->all();

        foreach ($items as $item) {
            foreach ($years as $year) {
                $newIncomeStatement[] = [
                    'year' => $year,
                    'item' => $item,
                    'value' => 1,
                    'name' => $item
                ];
            }
        }

        return $newIncomeStatement;
    }

    private function addMissingBalanceSheetItems(array $balanceSheet)
    {
        $newBalanceSheet = $balanceSheet;
        $items = ['currentCash', 'netIntangibleAssets', 'tangibleAssets', 'shortTermInvestments', 'currentAccountAndReceivables', 'totalAssets', 'currentPortionOfLongTermDebt', 'totalLiabilities'];
        $years = collect($balanceSheet)
            ->pluck('year')
            ->unique()
            ->all();

        foreach ($items as $item) {
            foreach ($years as $year) {
                $newBalanceSheet[] = [
                    'year' => $year,
                    'item' => $item,
                    'value' => 1,
                    'name' => $item
                ];
            }
        }

        return $newBalanceSheet;
    }

    private function addMissingCashFlowItems(array $cashFlow)
    {
        $newCashFlow = $cashFlow;
        $items = ['netIncome', 'capex', 'cashDividendPaid', 'deprecationAmortization', 'freeCashFlow', 'cashFromFinancing', 'cashFromInvesting'];
        $years = collect($cashFlow)
            ->pluck('year')
            ->unique()
            ->all();

        foreach ($items as $item) {
            foreach ($years as $year) {
                $newCashFlow[] = [
                    'year' => $year,
                    'item' => $item,
                    'value' => 1,
                    'name' => $item
                ];
            }
        }

        return $newCashFlow;
    }
}
