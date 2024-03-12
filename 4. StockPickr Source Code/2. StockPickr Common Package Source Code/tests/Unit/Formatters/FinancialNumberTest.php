<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Formatters\FinancialNumber;

class FinancialNumberTest extends TestCase
{
    /** @test */
    public function it_should_format_a_million_value()
    {
        $this->assertEquals('7.60M', $this->format(7.60));    
    }

    /** @test */
    public function it_should_format_a_ten_million_value()
    {
        $this->assertEquals('23.51M', $this->format(23.51));    
    }

    /** @test */
    public function it_should_format_a_hundred_million_value()
    {
        $this->assertEquals('872.00M', $this->format(872));    
    }

    /** @test */
    public function it_should_format_a_billion_value()
    {
        $this->assertEquals('1.03B', $this->format(1031));
    }

    /** @test */
    public function it_should_format_a_ten_billion_value()
    {
        $this->assertEquals('10.23B', $this->format(10231));
    }

    /** @test */
    public function it_should_format_a_hundred_billion_value()
    {
        $this->assertEquals('100.23B', $this->format(100231));
    }

    /** @test */
    public function it_should_format_a_thousand_billion_value()
    {
        $this->assertEquals('2,300.23B', $this->format(2300231));
    }

    /** @test */
    public function it_should_return_null_if_null_given()
    {
        $this->assertNull($this->format(null));
    }

    /** @test */
    public function it_should_return_0_if_0_given()
    {
        $this->assertEquals('0', $this->format(0));
    }

    /** @test */
    public function it_should_surround_a_negative_million_number_with_parentheses()
    {
        $this->assertEquals('(872.00M)', $this->format(-872));
    }

    /** @test */
    public function it_should_surround_a_negative_billion_number_with_parentheses()
    {
        $this->assertEquals('(1.56B)', $this->format(-1560));
    }

    // -------- Helpers --------

    private function format(?float $value): ?string
    {
        return FinancialNumber::create()->format($value);
    }
}