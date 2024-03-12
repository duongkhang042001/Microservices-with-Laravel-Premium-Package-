<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Formatters\Money;

class MoneyTest extends TestCase
{
    /** @test */
    public function it_should_format_a_price()
    {
        $this->assertEquals('$122.00', Money::create()->format(122));
    }

    /** @test */
    public function it_should_round_to_2_decimals()
    {
        $this->assertEquals('$122.72', Money::create()->format(122.719));
    }

    /** @test */
    public function it_should_use_thousand_separator()
    {
        $this->assertEquals('$3,451.00', Money::create()->format(3451));
    }

    /** @test */
    public function it_should_return_null_if_null_given()
    {
        $this->assertEquals(null, Money::create()->format(null));
    }

    /** @test */
    public function it_should_return_null_if_0_given()
    {
        $this->assertEquals(null, Money::create()->format(0));
    }

    /** @test */
    public function it_should_return_a_negative_number_in_parentheses()
    {
        $this->assertEquals('($1,230.12)', Money::create()->format(-1230.12));
    }
}
