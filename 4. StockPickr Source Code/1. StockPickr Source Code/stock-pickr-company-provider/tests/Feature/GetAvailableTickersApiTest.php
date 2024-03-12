<?php

namespace Tests\Feature;

use App\Services\MarketDataProvider;
use Mockery\MockInterface;
use Tests\TestCase;

class GetAvailableTickersApiTest extends TestCase
{
    /** @test */
    public function it_should_return_200()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAvailableTickers')
                ->andReturns([
                    'TST1',
                    'TST2'
                ]);
        });

        $response = $this->json('GET', '/api/v1/company-provider/available-tickers');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_return_peers()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAvailableTickers')
                ->andReturns([
                    'TST1',
                    'TST2'
                ]);
        });

        $peers = $this->json('GET', '/api/v1/company-provider/available-tickers')->json()['data'];
        $this->assertTrue(in_array('TST1', $peers));
        $this->assertTrue(in_array('TST2', $peers));
    }
}
