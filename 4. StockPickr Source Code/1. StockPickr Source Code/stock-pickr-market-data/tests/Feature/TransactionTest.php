<?php

namespace Tests\Feature;

use App\Events\DeleteCompany;
use App\Events\UpsertMarketData;
use App\Models\Analyst;
use App\Models\ShareData;
use App\Repositories\AnalystRepository;
use App\Repositories\ShareDataRepository;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_share_data_even_if_analyst_fails()
    {
        event(new UpsertMarketData($this->createUpsertMarketDataContainer([
            'marketData' => [
                'shareData' => [
                    'price'     => 100,
                    'marketCap' => 1000
                ],
                'analyst'   => []
            ]
        ]), true, 'abc-123'));
        $this->assertDatabaseHas('share_data', [
            'ticker'        => 'TST',
            'price'         => 100,
            'market_cap'    => 1000
        ]);

        $this->assertDatabaseCount('analysts', 0);
    }

    /** @test */
    public function it_should_not_store_analyst_if_share_data_fails()
    {
        try {
            event(new UpsertMarketData($this->createUpsertMarketDataContainer([
                'marketData' => [
                    'shareData' => [
                    ],
                    'analyst'   => [
                        'priceTarget' => [
                            'low'       => 1,
                            'average'   => 2,
                            'high'      => 3
                        ],
                        'rating' => [
                            'buy'             => 12,
                            'hold'            => 20,
                            'sell'            => 3
                        ]
                    ]
                ]
            ]), true, 'abc-123'));
        } catch (Exception $ex) {
            $this->assertDatabaseCount('analysts', 0);
            $this->assertDatabaseCount('share_data', 0);
        }
    }

    /** @test */
    public function it_should_rollback_if_error_happens_while_deleting_share_data()
    {
        Analyst::factory()->state(['ticker' => 'TST'])->create();
        ShareData::factory()->state(['ticker' => 'TST'])->create();

        $this->mock(ShareDataRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteByTicker')
                ->andThrow(new Exception('Error while deleting share data'));
        });

        try {
            event(new DeleteCompany('TST'));
        } catch (Exception) {
            $this->assertDatabaseCount('analysts', 1);
            $this->assertDatabaseCount('share_data', 1);
        }
    }

    /** @test */
    public function it_should_rollback_if_error_happens_while_deleting_analyst()
    {
        Analyst::factory()->state(['ticker' => 'TST'])->create();
        ShareData::factory()->state(['ticker' => 'TST'])->create();

        $this->mock(AnalystRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteByTicker')
                ->andThrow(new Exception('Error while deleting analyst'));
        });

        try {
            event(new DeleteCompany('TST'));
        } catch (Exception) {
            $this->assertDatabaseCount('analysts', 1);
            $this->assertDatabaseCount('share_data', 1);
        }
    }
}
