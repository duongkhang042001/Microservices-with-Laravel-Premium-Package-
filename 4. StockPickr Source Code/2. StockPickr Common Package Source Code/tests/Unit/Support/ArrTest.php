<?php

namespace Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use StockPickr\Common\Support\Arr;

class ArrTest extends TestCase
{
    /** @test */
    public function it_should_convert_an_object_to_an_array()
    {
        $obj = (object)[
            'ticker'    => 'TST',
            'name'      => 'Test Inc.'
        ];

        $arr = Arr::objectToArray($obj);

        $this->assertEquals([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.'
        ], $arr);
    }

    /** @test */
    public function it_should_convert_a_nested_object_to_an_array()
    {
        $obj = (object)[
            'ticker'        => 'TST',
            'name'          => 'Test Inc.',
            'shareData' => (object)[
                'price'     => 10,
                'marketCap' => 100
            ]
        ];

        $arr = Arr::objectToArray($obj);

        $this->assertEquals([
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'shareData' => [
                'price'     => 10,
                'marketCap' => 100
            ]
        ], $arr);
    }

    /** @test */
    public function it_should_convert_an_object_with_a_collection_to_array()
    {
        $obj = (object)[
            'ticker'    => 'TST',
            'peers'     => collect(['TST1', 'TST2'])
        ];

        $arr = Arr::objectToArray($obj);

        $this->assertEquals([
            'ticker'    => 'TST',
            'peers'     => ['TST1', 'TST2']
        ], $arr);
    }
}