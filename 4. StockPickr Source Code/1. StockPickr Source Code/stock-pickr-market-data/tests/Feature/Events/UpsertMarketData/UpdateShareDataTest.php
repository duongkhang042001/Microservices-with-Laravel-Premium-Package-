<?php

namespace Tests\Feature\Events\UpsertMarketData;

use App\Events\UpsertMarketData;
use App\Models\ShareData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateShareDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_update_an_existing_share_data()
    {
        $data = [
            'ticker'        => 'TST',
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
        ];
        event(new UpsertMarketData($this->createUpsertMarketDataContainer($data), true, 'abc-123'));

        $data['marketData']['shareData']['price'] = 112.5;
        $data['marketData']['shareData']['marketCap'] = 15420.1;

        event(new UpsertMarketData($this->createUpsertMarketDataContainer($data), true, 'abc-123'));

        $this->assertDatabaseHas('share_data', [
            'ticker'                => 'TST',
            'price'                 => 112.5,
            'market_cap'            => 15420.1,
            'shares_outstanding'    => 1320,
            'beta'                  => 2.973
        ]);
    }

    /** @test */
    public function it_should_not_override_optional_fields_with_null()
    {
        $data = [
            'ticker'        => 'TST',
            'marketData' => [
                'shareData'    => [
                    'price'                 => 100.2310,
                    'marketCap'             => 13472.572199,
                    'sharesOutstanding'     => 1320,
                    'beta'                  => 2
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
        ];

        event(new UpsertMarketData($this->createUpsertMarketDataContainer($data), true, 'abc-123'));

        $data['marketData']['shareData']['price'] = 150;
        $data['marketData']['shareData']['marketCap'] = 15420.1;
        unset($data['marketData']['shareData']['sharesOutstanding']);
        $data['marketData']['shareData']['beta'] = null;

        event(new UpsertMarketData($this->createUpsertMarketDataContainer($data), true, 'abc-123'));

        $this->assertDatabaseHas('share_data', [
            'ticker'                => 'TST',
            'price'                 => 150,
            'market_cap'            => 15420.1,
            'shares_outstanding'    => 1320,
            'beta'                  => 2
        ]);
    }

    /**
     * @test
     * @dataProvider invalidDataProvider()
     */
    public function it_should_throw_an_exception_if_new_data_invalid($price, $marketCap)
    {
        try {
            ShareData::factory()
                ->state([
                    'ticker'        => 'TST',
                    'price'         => 100,
                    'market_cap'    => 1000
                ])
                ->create();

            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'ticker'    => 'TST',
                'marketData' => [
                    'shareData' => [
                        'price'     => $price,
                        'marketCap' => $marketCap
                    ],
                    'analyst'   => [
                        'priceTargetLow'        => 1,
                        'priceTargetAverage'    => 2,
                        'priceTargetHigh'       => 3,

                        'ratingBuy'             => 12,
                        'ratingHold'            => 20,
                        'ratingSell'            => 3,
                    ]
                ]
            ]), true, 'abc-123'));
        } catch (ValidationException) {
            $this->mockRedisService('publishMarketDataUpdateFailed');
            $this->assertDatabaseHas('share_data', [
                'price'         => 100,
                'market_cap'    => 1000
            ]);
        }

    }

    public function invalidDataProvider()
    {
        // price, market cap
        return [
            [null, 1000],
            [0, 1000],
            // ['', 1000],

            [1000, null],
            [1000, 0],
            // [1000, '']
        ];
    }
}
