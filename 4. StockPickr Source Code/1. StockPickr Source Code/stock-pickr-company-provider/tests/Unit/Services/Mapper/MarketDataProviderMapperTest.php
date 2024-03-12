<?php


namespace Tests\Unit\Services\Mapper;

use App\Services\Mapper\MarketDataProviderMapper;
use Tests\Mocks\DummyMarketDataProviderMapper;
use Tests\TestCase;

class MarketDataProviderMapperTest extends TestCase
{
    private MarketDataProviderMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = resolve(DummyMarketDataProviderMapper::class);
    }

    /** @test */
    public function it_can_parse_a_single_item()
    {
        $item = [
            'totalAssets'   => 1000,
            'totalEquity'   => 200
        ];

        $value = $this->invokeMethod($this->mapper, 'parseItem', [$item, 'totalAssets']);

        $this->assertEquals(1000, $value);
    }

    /** @test */
    public function it_can_parse_a_standard_array_item()
    {
        $item = [
            'totalAssets'   => 1000,
            'totalEquity'   => 200
        ];

        $value = $this->invokeMethod($this->mapper, 'parseArrayItem', [$item, ['totalAssets', 'totalEquity']]);

        $this->assertEquals(1200, $value);
    }

    /** @test */
    public function it_can_parse_a_custom_function_in_the_end_of_array_item()
    {
        $item = [
            'totalAssets'       => 1000,
            'intangibleAssets'  => 400
        ];

        $value = $this->invokeMethod($this->mapper, 'parseArrayItem', [$item, ['totalAssets', 'intangibleAssets', fn ($a, $b) => $a - $b]]);

        $this->assertEquals(600, $value);
    }

    /** @test */
    public function it_can_convert_to_units()
    {
        $item = [
            'totalAssets'   => 100000000,
            'totalEquity'   => 3500000000,
            'longTermDebt'  => 12000000,
            'shortTermDebt' => 0,
            'foo'           => null,
            'bar'           => '',
            'baz'           => 'invalid'
        ];

        $replacedItem = $this->invokeMethod($this->mapper, 'normalize', [$item]);

        $this->assertSame(100.0, $replacedItem['totalAssets']);
        $this->assertSame(3500.0, $replacedItem['totalEquity']);
        $this->assertSame(12.0, $replacedItem['longTermDebt']);
        $this->assertSame(0.0, $replacedItem['shortTermDebt']);

        $this->assertSame(0.0, $replacedItem['foo']);
        $this->assertSame(0.0, $replacedItem['bar']);
        $this->assertSame(0.0, $replacedItem['baz']);
    }

    /** @test */
    public function it_can_map_statements()
    {
        $statements = [
            ['totalRevenue' => 1000000000, 'costOfRevenue' => 100000000, 'year' => 2015],
            ['totalRevenue' => 1100000000, 'costOfRevenue' => 110000000, 'year' => 2016],
            ['totalRevenue' => 1210000000, 'costOfRevenue' => 121000000, 'year' => 2017],
            ['totalRevenue' => 1331000000, 'costOfRevenue' => 133000000, 'year' => 2018],
            ['totalRevenue' => null,       'costOfRevenue' => '',        'year' => 2019]
        ];

        $mapped = $this->mapper->mapStatement($statements, 'incomeStatement');

        $this->assertEquals([
            2015 => ['total_revenue' => 1000, 'cost_of_revenue' => 100, 'gross_profit' => 900],
            2016 => ['total_revenue' => 1100, 'cost_of_revenue' => 110, 'gross_profit' => 990],
            2017 => ['total_revenue' => 1210, 'cost_of_revenue' => 121, 'gross_profit' => 1089],
            2018 => ['total_revenue' => 1331, 'cost_of_revenue' => 133, 'gross_profit' => 1198],
            2019 => ['total_revenue' => 0,    'cost_of_revenue' => 0,   'gross_profit'    => 0]
        ], $mapped);
    }
}
