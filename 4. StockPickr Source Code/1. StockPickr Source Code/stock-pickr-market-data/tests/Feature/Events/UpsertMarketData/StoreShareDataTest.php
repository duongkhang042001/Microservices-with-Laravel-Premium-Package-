<?php

namespace Tests\Feature\Events\UpsertMarketData;

use App\Events\UpsertMarketData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreShareDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_publish_market_data_created_event()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
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
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('share_data', [
            'ticker'                => 'TST',
            'price'                 => 100.23,
            'market_cap'            => 13472.5722,
            'shares_outstanding'    => 1320,
            'beta'                  => 2.973
        ]);
    }

    /** @test */
    public function it_should_store_share_data()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
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
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('share_data', [
            'ticker'                => 'TST',
            'price'                 => 100.23,
            'market_cap'            => 13472.5722,
            'shares_outstanding'    => 1320,
            'beta'                  => 2.973
        ]);
    }

    /** @test */
    public function it_should_store_null_if_optional_fields_are_missing()
    {
        $this->mockRedisService('publishMarketDataCreated');

        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'marketData' => [
                'shareData'    => [
                    'price'                 => 100.2310,
                    'marketCap'             => 13472.572199
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
                ],
            ]
        ]), false, 'abc-123'));

        $this->assertDatabaseHas('share_data', [
            'ticker'                => 'TST',
            'price'                 => 100.23,
            'market_cap'            => 13472.5722,
            'shares_outstanding'    => null,
            'beta'                  => null
        ]);
    }

    /**
     * @test
     * @dataProvider invalidDataProvider()
     */
    public function it_should_throw_an_exception_if_data_invalid($price, $marketCap)
    {
        try {
            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'marketData' => [
                    'shareData' => [
                        'price'     => $price,
                        'marketCap' => $marketCap
                    ],
                    'analyst'   => [
                        'priceTarget' => [
                            'low'       => 1,
                            'average'   => 2,
                            'high'      => 3,
                        ],
                        'rating' => [
                            'buy'             => 12,
                            'hold'            => 20,
                            'sell'            => 3
                        ]
                    ]
                ]
            ]), false, 'abc-123'));
        } catch (ValidationException) {
            $this->mockRedisService('publishMarketDataCreateFailed');
            $this->assertDatabaseCount('share_data', 0);
        }

    }

    public function invalidDataProvider()
    {
        // price, market cap
        return [
            [null, 1000],
            [0, 1000],
            ['', 1000],

            [1000, null],
            [1000, 0],
            [1000, '']
        ];
    }
}
