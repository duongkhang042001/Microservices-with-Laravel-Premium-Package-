<?php

namespace Tests\Feature\Events\UpsertMarketData;

use App\Events\UpsertMarketData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreAnalystTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_publish_market_data_created_event()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'ticker'    => 'TST',
            'marketData' => [
                'analyst'   => [
                    'priceTarget' => [
                        'low'       => 100.25,
                        'average'   => 150,
                        'high'      => 200.1
                    ],
                    'rating' => [
                        'buy'             => 12,
                        'hold'            => 20,
                        'sell'            => 3
                    ]
                ],
                'shareData' => [
                    'price'     => 100,
                    'marketCap' => 1000
                ]
            ]
        ]), false, 'abc-123'));
    }

    /** @test */
    public function it_should_store_analyst()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'ticker'    => 'TST',
            'marketData' => [
                'analyst'   => [
                    'priceTarget' => [
                        'low'       => 100.25,
                        'average'   => 150,
                        'high'      => 200.1
                    ],
                    'rating' => [
                        'buy'             => 12,
                        'hold'            => 20,
                        'sell'            => 3
                    ]
                ],
                'shareData' => [
                    'price'     => 100,
                    'marketCap' => 1000
                ]
            ]
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('analysts', [
            'ticker'                => 'TST',
            'price_target_low'      => 100.25,
            'price_target_average'  => 150.00,
            'price_target_high'     => 200.10,

            'buy'                   => 12,
            'hold'                  => 20,
            'sell'                  => 3,

            'number_of_analysts'    => 35
        ]);
    }

    /** @test */
    public function it_should_store_null_if_optional_fields_are_missing()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'ticker'     => 'TST',
            'marketData' => [
                'analyst'   => [
                    'priceTarget' => [
                        'average' => 150
                    ],
                    'rating' => [
                        'buy'             => 12,
                        'hold'            => 20,
                        'sell'            => 3
                    ]
                ],
                'shareData' => [
                    'price'     => 100,
                    'marketCap' => 1000
                ]
            ]
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('analysts', [
            'ticker'                => 'TST',
            'price_target_low'      => null,
            'price_target_average'  => 150.00,
            'price_target_high'     => null,

            'buy'                   => 12,
            'hold'                  => 20,
            'sell'                  => 3,

            'number_of_analysts'    => 35
        ]);
    }

    /**
     * @test
     * @dataProvider invalidPriceTargetProvider()
     */
    public function it_should_throw_an_exception_if_price_target_invalid(int $low, $average, int $high)
    {
        try {
            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'ticker'    => 'TST',
                'marketData' => [
                    'analyst'   => [
                        'priceTarget' => [
                            'low'       => $low,
                            'average'   => $average,
                            'high'      => $high
                        ],
                        'rating' => [
                            'buy'             => 12,
                            'hold'            => 20,
                            'sell'            => 3
                        ]
                    ],
                    'shareData' => [
                        'price'     => 100,
                        'marketCap' => 1000
                    ]
                ]
            ]), false, 'abc-123'));
        } catch (ValidationException) {
            $this->mockRedisService('publishMarketDataCreatFailed');
            $this->assertDatabaseCount('analysts', 0);
        }
    }

    public function invalidPriceTargetProvider()
    {
        // low, average, high
        return [
            [10, null, 30],
            [10, '', 30],
            [10, 0, 30]
        ];
    }

    /**
     * @test
     * @dataProvider invalidDataProvider()
     */
    public function it_should_throw_an_exception_if_data_invalid($buy, $hold, $sell, $priceTarget)
    {
        try {
            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'ticker'    => 'TST',
                'marketData' => [
                    'analyst'   => [
                        'priceTarget' => [
                            'low'       => 10,
                            'average'   => $priceTarget,
                            'high'      => 30
                        ],
                        'rating' => [
                            'buy'             => $buy,
                            'hold'            => $hold,
                            'sell'            => $sell,
                        ]
                    ],
                    'shareData' => [
                        'price'     => 100,
                        'marketCap' => 1000
                    ]
                ]
            ]), false, 'abc-123'));
        } catch (ValidationException) {
            $this->mockRedisService('publishMarketDataCreatFailed');
            $this->assertDatabaseCount('analysts', 0);
        }
    }

    /** @test */
    public function it_should_throw_an_exception_if_all_ratings_are_zero()
    {
        try {
            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'ticker'    => 'TST',
                'marketData' => [
                    'analyst'   => [
                        'priceTarget' => [
                            'low'       => 10,
                            'average'   => 20,
                            'high'      => 30
                        ],
                        'rating' => [
                            'buy'             => 0,
                            'hold'            => 0,
                            'sell'            => 0
                        ]
                    ],
                    'shareData' => [
                        'price'     => 100,
                        'marketCap' => 1000
                    ]
                ]
            ]), false, 'abc-123'));
        } catch (ValidationException) {
            $this->mockRedisService('publishMarketDataCreatFailed');
            $this->assertDatabaseCount('analysts', 0);
        }
    }

    public function invalidDataProvider()
    {
        // buy, hold, sell, price target, average
        return [
            [null, 2, 3, 100],
            [1, null, 3, 100],
            [1, 2, null, 100],
            [1, 2,    3, null],

            ['', 2, 3, 100],
            [1, '', 3, 100],
            [1, 2, '', 100],
            [1, 2,  3, ''],
        ];
    }
}
