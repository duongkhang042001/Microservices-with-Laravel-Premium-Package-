<?php

namespace Tests\Feature\Events\UpsertMarketData;

use App\Events\UpsertMarketData;
use App\Models\Analyst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAnalystTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_publish_market_data_updated_event()
    {
        $this->mockRedisService('publishMarketDataUpdated');

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
        ]), true, 'abc-123'));
    }

    /** @test */
    public function it_should_publish_market_data_updated_even_when_analyst_failed()
    {
        $this->mockRedisService('publishMarketDataUpdated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'ticker'    => 'TST',
            'marketData' => [
                'analyst'   => [
                ],
                'shareData' => [
                    'price'     => 100,
                    'marketCap' => 1000
                ]
            ]
        ]), true, 'abc-123'));
    }

    /** @test */
    public function it_should_update_an_existing_analyst()
    {
        $data = $this->createUpsertMarketDataContainer([
            'marketData' => [
                'shareData'    => [
                    'price'                 => 100.2310,
                    'marketCap'             => 13472.572199,
                    'sharesOutstanding'     => 1320,
                    'beta'                  => 2.973
                ],
                'analyst'   => [
                    'priceTarget' => [
                        'average' => 150
                    ],
                    'rating' => [
                        'buy'             => 12,
                        'hold'            => 20,
                        'sell'            => 3
                    ]
                ]
            ]
        ]);

        event(new UpsertMarketData($data, true, 'abc-123'));

        $data->marketData->analyst->priceTarget->average = 175;
        $data->marketData->analyst->rating->buy = 22;

        event(new UpsertMarketData($data, true, 'abc-123'));

        $this->assertDatabaseHas('analysts', [
            'ticker'                => 'TST',
            'price_target_average'  => 175,
            'buy'                   => 22,
            'price_target_high'     => null,
            'price_target_low'      => null,
            'sell'                  => 3,
            'hold'                  => 20,
        ]);
        $this->assertDatabaseCount('analysts', 1);
    }

    /** @test */
    public function it_should_not_override_optional_fields_with_null()
    {
        $data = $this->createUpsertMarketDataContainer([
            'marketData' => [
                'shareData'    => [
                    'price'                 => 100.2310,
                    'marketCap'             => 13472.572199,
                    'sharesOutstanding'     => 1320,
                    'beta'                  => 2
                ],
                'analyst'   => [
                    'priceTarget' => [
                        'low'       => 100,
                        'average'   => 150,
                        'high'      => 200
                    ],
                    'rating' => [
                        'buy'             => 12,
                        'hold'            => 20,
                        'sell'            => 3
                    ]
                ]
            ]
        ]);

        event(new UpsertMarketData($data, true, 'abc-123'));

        $data->marketData->analyst->priceTarget->average = 166;
        $data->marketData->analyst->priceTarget->low = null;
        $data->marketData->analyst->priceTarget->high = null;

        event(new UpsertMarketData($data, true, 'abc-123'));

        $this->assertDatabaseHas('analysts', [
            'ticker'                => 'TST',
            'price_target_average'  => 166,
            'price_target_low'      => 100,
            'price_target_high'     => 200,
        ]);
    }

    /**
     * @test
     * @dataProvider invalidDataProvider()
     */
    public function it_should_not_update_invalid_analyst_data($buy, $hold, $sell, $priceTarget)
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'buy'                   => 1,
                'hold'                  => 2,
                'sell'                  => 3,
                'price_target_average'  => 100
            ])
            ->create();

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'marketData' => [
                'shareData' => [
                    'price'     => 10,
                    'marketCap' => 100
                ],
                'analyst'   => [
                    'priceTargetAverage'    => $priceTarget,
                    'ratingBuy'                   => $buy,
                    'ratingHold'                  => $hold,
                    'ratingSell'                  => $sell,
                ]
            ]
        ]), true, 'abc-123'));

        $this->assertDatabaseHas('analysts', [
            'ticker'                => 'TST',
            'buy'                   => 1,
            'hold'                  => 2,
            'sell'                  => 3,
            'price_target_average'  => 100
        ]);
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
