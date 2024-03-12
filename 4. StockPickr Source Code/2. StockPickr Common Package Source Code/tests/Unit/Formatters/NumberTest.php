<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Formatters\Number;

class NumberTest extends TestCase
{
    /** @test */
    public function it_should_format_an_integer_value()
    {
        $this->assertEquals('150.00', Number::create()->format(150));
    }    

    /** @test */
    public function it_should_format_a_float_value()
    {
        $this->assertEquals('150.24', Number::create()->format(150.24));
    }

    /** @test */
    public function it_should_appl_decimal_points()
    {
        $this->assertEquals('150', Number::create(['decimals' => 0])->format(150));
    }

    /** @test */
    public function it_should_add_thousand_separator()
    {
        $this->assertEquals('10,230.12', Number::create()->format(10230.12));
    }

    /** @test */
    public function it_should_return_null_if_null_given()
    {
        $this->assertNull(Number::create()->format(null));
    }

    /** @test */
    public function it_should_return_0_if_0_given()
    {
        $this->assertEquals('0', Number::create()->format(0));
    }
}