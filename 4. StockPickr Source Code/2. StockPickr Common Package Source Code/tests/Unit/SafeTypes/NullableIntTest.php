<?php

namespace Tests\Unit\SafeTypes;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\SafeTypes\NullableInt;

class NullableIntTest extends TestCase
{
    /** @test */
    public function it_should_return_null_if_empty_value_given()
    {
        $this->assertNull(NullableInt::create()->format(''));
        $this->assertNull(NullableInt::create()->format(null));
    }

    /** @test */
    public function it_should_not_consider_0_as_empty_value()
    {
        $this->assertSame(0, NullableInt::create()->format(0));
    }

    /** @test */
    public function it_should_return_an_float_value()
    {
        $this->assertSame(1, NullableInt::create()->format(1));
        $this->assertSame(3, NullableInt::create()->format(3.14));
    }
}
