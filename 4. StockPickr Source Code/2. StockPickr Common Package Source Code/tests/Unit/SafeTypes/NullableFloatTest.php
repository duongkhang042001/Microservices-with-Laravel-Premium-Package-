<?php

namespace Tests\Unit\SafeTypes;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\SafeTypes\NullableFloat;

class NullableFloatTest extends TestCase
{
    /** @test */
    public function it_should_return_null_if_empty_value_given()
    {
        $this->assertNull(NullableFloat::create()->format(''));
        $this->assertNull(NullableFloat::create()->format(null));
    }

    /** @test */
    public function it_should_not_consider_0_as_empty_value()
    {
        $this->assertSame(0.0, NullableFloat::create()->format(0));
    }

    /** @test */
    public function it_should_return_a_float_value()
    {
        $this->assertSame(1.0, NullableFloat::create()->format(1));
        $this->assertSame(3.14, NullableFloat::create()->format(3.14));
    }
}
