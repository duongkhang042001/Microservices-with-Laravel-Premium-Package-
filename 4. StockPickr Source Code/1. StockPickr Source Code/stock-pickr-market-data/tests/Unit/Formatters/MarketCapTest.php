<?php

namespace Tests\Unit\Formatters;

use App\Formatters\MarketCap;
use Tests\TestCase;

class MarketCapTest extends TestCase
{
    /** @test */
    public function it_should_format_market_cap_in_billion_scale()
    {
        $this->assertEquals('$5B', MarketCap::create()->format(5200));
    }

    /** @test */
    public function it_should_format_market_cap_in_million_scale()
    {
        $this->assertEquals('$570M', MarketCap::create()->format(570));
    }

    /** @test */
    public function it_should_return_null_if_null_given()
    {
        $this->assertNull(MarketCap::create()->format(null));
    }

    /** @test */
    public function it_should_return_null_if_0_given()
    {
        $this->assertNull(MarketCap::create()->format(0));
    }
}
