<?php

namespace Tests\Unit\Services;

use App\Services\ValueCalculatorService;
use Tests\TestCase;

class ValueCalculatorServiceTest extends TestCase
{
    /**
     * @var ValueCalculatorService
     */
    private $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new ValueCalculatorService;
    }

    /** @test */
    public function it_returns_a_ratio()
    {
        $this->assertEquals(0.5, $this->calculator->divide(1, 2));
    }

    /** @test */
    public function it_should_fallback_to_a_default_value()
    {
        $this->assertEquals(99, $this->calculator->divide(1, 0, 99));
    }
}
