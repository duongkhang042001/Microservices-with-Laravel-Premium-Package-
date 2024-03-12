<?php

namespace Tests\Unit\Formatters;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Formatters\Percent;

class PercentTest extends TestCase
{
    /** @test */
    public function it_should_format_an_integer()
    {
        $this->assertEquals('23.00%', Percent::create()->format(0.23));
    }

    /** @test */
    public function it_should_format_a_float()
    {
        $this->assertEquals('23.46%', Percent::create()->format(0.23459));
    }

    /** @test */
    public function it_should_return_null_if_null_given()
    {
        $this->assertEquals(null, Percent::create()->format(null));
    }

    /** @test */
    public function it_should_return_0_if_0_given()
    {
        $this->assertEquals('0.00%', Percent::create()->format(0));
    }

    /** @test */
    public function it_shuld_use_decimals_from_options()
    {
        $value = Percent::create(['decimals' => 0])->format(0.12799);
        $this->assertEquals('13%', $value);
    }
}
