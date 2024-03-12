<?php

namespace Tests\Feature\Api;

use App\Http\Resources\ShareDataResource;
use App\Models\ShareData;
use StockPickr\Common\Services\CacheService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery\MockInterface;

class GetShareDataApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $response = $this->json('GET', '/api/v1/share-data/TST');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_404_if_share_data_not_found_for_company()
    {
        $response = $this->json('GET', '/api/v1/share-data/INVLD');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_market_cap()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];

        $this->assertSame(12540.432, $data['marketCap']['raw']);
        $this->assertSame('$13B', $data['marketCap']['formatted']);
    }

    /** @test */
    public function it_should_return_market_cap_for_a_smaller_company()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 872.12,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];

        $this->assertSame(872.12, $data['marketCap']['raw']);
        $this->assertSame('$872M', $data['marketCap']['formatted']);
    }

    /** @test */
    public function it_should_return_price()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];

        $this->assertSame(120.34, $data['price']['raw']);
        $this->assertSame('$120.34', $data['price']['formatted']);
    }

    /** @test */
    public function it_should_return_beta()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];

        $this->assertSame(1.68, $data['beta']);
    }

    /** @test */
    public function it_should_return_shares_outstanding()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => 1.68
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];

        $this->assertSame(43110, $data['sharesOutstanding']);
    }

    /** @test */
    public function it_should_return_null_as_beta_if_missing()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => 43110,
                'beta'                  => null
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];
        $this->assertNull($data['beta']);
    }

    /** @test */
    public function it_should_return_null_as_shares_outstanding_if_missing()
    {
        ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => null,
                'beta'                  => 1
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];
        $this->assertNull($data['sharesOutstanding']);
    }

    /** @test */
    public function it_should_use_cache()
    {
        $shareData = ShareData::factory()
            ->state([
                'ticker'                => 'TST',
                'price'                 => 120.34,
                'market_cap'            => 12540.432,
                'shares_outstanding'    => null,
                'beta'                  => 1
            ])
            ->create();

        $shareData->price = 1;
        $resource = new ShareDataResource($shareData);

        $this->mock(CacheService::class, function (MockInterface $mock) use ($resource) {
            $mock->shouldReceive('getOrRemember')
                ->andReturn($resource);
        });

        $data = $this->json('GET', '/api/v1/share-data/TST')->json()['data'];
        $this->assertEquals(1, $data['price']['raw']);
    }
}
