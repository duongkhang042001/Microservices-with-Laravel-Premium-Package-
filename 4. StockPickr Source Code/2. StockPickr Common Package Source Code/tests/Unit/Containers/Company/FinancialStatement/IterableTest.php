<?php

namespace Tests\Unit\Conttainers\Company\FinancialStatement;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Containers\Company\FinancialStatement\IncomeStatement\IncomeStatementContainer;

class IterableTest extends TestCase
{
    /** @test */
    public function it_should_be_iterable()
    {
        $container = IncomeStatementContainer::from([
            'totalRevenue' => ['name' => 'totalRevenue', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'costOfRevenue' => ['name' => 'costOfRevenue', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'grossProfit' => ['name' => 'grossProfit', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'operatingIncome' => ['name' => 'operatingIncome', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'pretaxIncome' => ['name' => 'pretaxIncome', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'incomeTax' => ['name' => 'incomeTax', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'interestExpense' => ['name' => 'interestExpense', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'researchAndDevelopment' => ['name' => 'researchAndDevelopment', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'sellingGeneralAdministrative' => ['name' => 'sellingGeneralAdministrative', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'netIncome' => ['name' => 'netIncome', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'ebit' => ['name' => 'ebit', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
            'eps' => ['name' => 'eps', 'formatter' => 'financialNumber', 'shouldHighlightNegativeValue' => false, 'isInverted' => false, 'data' => [2020 => ['rawValue' => 100, 'formattedValue' => '100M']]],
        ]);

        foreach ($container as $slug => $item) {
            $this->assertEquals($slug, $item->name);
        }
    }
}