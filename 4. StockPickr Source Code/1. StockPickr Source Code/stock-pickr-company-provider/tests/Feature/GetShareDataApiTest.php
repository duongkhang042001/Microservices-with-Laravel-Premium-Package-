<?php

namespace Tests\Feature;

use App\Services\MarketDataProvider;
use Mockery\MockInterface;
use StockPickr\Common\Containers\ShareDataContainer;
use Tests\TestCase;

class GetShareDataApiTest extends TestCase
{
    /** @test */
    public function it_should_return_200()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getShareData')
                ->with('TST')
                ->andReturns(ShareDataContainer::from([
                    'price'     => 87.54
                ]));
        });

        $response = $this->json('GET', '/api/v1/company-provider/companies/TST/share-data');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_return_market_data()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getShareData')
                ->with('TST')
                ->andReturns(ShareDataContainer::from([
                    'price'             => '87.54',
                    'beta'              => '1.23627',
                    'marketCap'         => '2049155',
                    'sharesOutstanding' => '16788.096'
                ]));
        });

        $shareData = $this->json('GET', '/api/v1/company-provider/companies/TST/share-data')->json()['data'];
        $this->assertSame(87.54, $shareData['price']);
        $this->assertSame(1.23627, $shareData['beta']);
        $this->assertSame(2049155, $shareData['marketCap']);
        $this->assertSame(16788.096, $shareData['sharesOutstanding']);
    }

    /** @test */
    public function it_should_return_market_data_item_as_null_if_missing()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getShareData')
                ->with('TST')
                ->andReturns(ShareDataContainer::from([
                    'price'             => '',
                    'beta'              => null,
                    'marketCap'         => '2049155',
                    'sharesOutstanding' => '16788.096'
                ]));
        });

        $shareData = $this->json('GET', '/api/v1/company-provider/companies/TST/share-data')->json()['data'];
        $this->assertNull($shareData['price']);
        $this->assertNull($shareData['beta']);
    }
}
