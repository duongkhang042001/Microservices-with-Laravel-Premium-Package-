<?php

namespace Tests\Feature;

use App\Services\MarketDataProvider;
use Mockery\MockInterface;
use StockPickr\Common\Containers\AnalystContainer;
use Tests\TestCase;

class GetAnalystApiTest extends TestCase
{
    /** @test */
    public function it_should_return_200()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAnalyst')
                ->with('TST')
                ->andReturns(AnalystContainer::from([
                    'priceTarget'   => [],
                    'rating'        => []
                ]));
        });

        $response = $this->json('GET', '/api/v1/company-provider/companies/TST/analyst');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_return_price_target()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAnalyst')
                ->with('TST')
                ->andReturns(AnalystContainer::from([
                    'priceTarget' => [
                        'low'       => 1,
                        'average'   => 2,
                        'high'      => 3
                    ],
                    'rating' => [
                        'buy'   => 10,
                        'hold'  => 20,
                        'sell'  => 30,
                        'date'  => '2021-05-05'
                    ]
                ]));
        });

        $analystAsArray = $this->json('GET', '/api/v1/company-provider/companies/TST/analyst')->json()['data'];
        $analyst = AnalystContainer::from($analystAsArray);

        $this->assertEquals(1, $analyst->getPriceTargetLow());
        $this->assertEquals(2, $analyst->getPriceTargetAverage());
        $this->assertEquals(3, $analyst->getPriceTargetHigh());
    }

    /** @test */
    public function it_should_return_rating()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAnalyst')
                ->with('TST')
                ->andReturns(AnalystContainer::from([
                    'priceTarget' => [
                        'low'       => 1,
                        'average'   => 2,
                        'high'      => 3
                    ],
                    'rating' => [
                        'buy'   => 10,
                        'hold'  => 20,
                        'sell'  => 30,
                        'date'  => '2021-05-05'
                    ]
                ]));
        });

        $analystAsArray = $this->json('GET', '/api/v1/company-provider/companies/TST/analyst')->json()['data'];
        $analyst = AnalystContainer::from($analystAsArray);

        $this->assertEquals(10, $analyst->getBuy());
        $this->assertEquals(20, $analyst->getHold());
        $this->assertEquals(30, $analyst->getSell());
    }

    /** @test */
    public function it_should_return_data_as_null_if_missing()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAnalyst')
                ->with('TST')
                ->andReturns(AnalystContainer::from([
                    'priceTarget' => [
                        'low'       => null,
                        'average'   => '',
                        'high'      => 3
                    ],
                    'rating' => [
                        'buy'   => null,
                        'hold'  => '',
                        'sell'  => 30,
                        'date'  => '2021-05-05'
                    ]
                ]));
        });

        $analystAsArray = $this->json('GET', '/api/v1/company-provider/companies/TST/analyst')->json()['data'];
        $analyst = AnalystContainer::from($analystAsArray);

        $this->assertNull($analyst->getPriceTargetLow());
        $this->assertNull($analyst->getPriceTargetAverage());

        $this->assertNull($analyst->getBuy());
        $this->assertNull($analyst->getHold());
    }

}
