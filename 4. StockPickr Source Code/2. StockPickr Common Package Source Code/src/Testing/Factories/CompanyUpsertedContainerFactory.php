<?php

namespace StockPickr\Common\Testing\Factories;

use Illuminate\Support\Arr;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

final class CompanyUpsertedContainerFactory
{
    public function createCompanyUpsertedContainer(
        array $data
    ): CompanyUpsertedContainer {
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

    private function createIncomeStatement(array $data): array
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

    private function createBalancSheet(array $data): array
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

    private function createCashFlow(array $data): array
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

    private function createItem(string $name, array | float $value): array
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
}