<?php

namespace Tests\Unit\Conttainers\Company\FinancialStatement;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Containers\Company\FinancialStatement\FinancialStatementItemContainer;

class FinancialStatementItemContainerTest extends TestCase
{
    /** @test */
    public function it_should_build_a_valid_container_with_data()
    {
        $item = FinancialStatementItemContainer::from([
            'formatter' => 'financialNumber',
            'name' => 'Total Revenue',
            'shouldHighlightNegativeValue' => true,
            'isInverted' => false,
            'data' => [
                2017 => [
                    'rawValue' => 100,
                    'formattedValue' => '100M',
                    'classes' => ['some-class'],
                ],
                2018 => [
                    'rawValue' => 110,
                    'formattedValue' => '110M'
                ],
            ]
        ]);

        $this->assertEquals(100, $item->data[2017]->rawValue);
        $this->assertEquals('100M', $item->data[2017]->formattedValue);
        $this->assertEquals(['some-class'], $item->data[2017]->classes);

        $this->assertEquals(110, $item->data[2018]->rawValue);
        $this->assertEquals('110M', $item->data[2018]->formattedValue);
        $this->assertEquals([], $item->data[2018]->classes);
    }
}