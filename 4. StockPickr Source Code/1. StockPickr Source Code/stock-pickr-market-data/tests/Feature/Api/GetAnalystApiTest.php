<?php

namespace Tests\Feature\Api;

use App\Http\Resources\AnalystResource;
use App\Models\Analyst;
use StockPickr\Common\Services\CacheService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery\MockInterface;

class GetAnalystApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => 100,
                'price_target_average'  => 150,
                'price_target_high'     => 200,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30
            ])
            ->create();

        $response = $this->json('GET', '/api/v1/analyst/TST');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_404_if_analyst_not_found_for_company()
    {
        $response = $this->json('GET', '/api/v1/analyst/INVLD');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_price_targets()
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => 100.28,
                'price_target_average'  => 150.331,
                'price_target_high'     => 200,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/analyst/TST')->json()['data'];

        $this->assertSame(100.28, $data['priceTarget']['low']['raw']);
        $this->assertSame(150.33, $data['priceTarget']['average']['raw']);
        $this->assertSame(200, $data['priceTarget']['high']['raw']);
    }

    /** @test */
    public function it_should_return_formatted_price_targets()
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => 100.28,
                'price_target_average'  => 150.331,
                'price_target_high'     => 200,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/analyst/TST')->json()['data'];

        $this->assertEquals('$100.28', $data['priceTarget']['low']['formatted']);
        $this->assertEquals('$150.33', $data['priceTarget']['average']['formatted']);
        $this->assertEquals('$200.00', $data['priceTarget']['high']['formatted']);
    }

    /** @test */
    public function it_should_return_missing_price_targets_as_null()
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => null,
                'price_target_average'  => 150.331,
                'price_target_high'     => null,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/analyst/TST')->json()['data'];

        $this->assertNull($data['priceTarget']['low']['raw']);
        $this->assertNull($data['priceTarget']['low']['formatted']);

        $this->assertNull($data['priceTarget']['high']['raw']);
        $this->assertNull($data['priceTarget']['high']['formatted']);
    }

    /** @test */
    public function it_should_return_ratings()
    {
        Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => 100.28,
                'price_target_average'  => 150.331,
                'price_target_high'     => 200,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30,
                'number_of_analysts'    => 60,
                'rating_date'           => '2021-04-18 12:33:01'
            ])
            ->create();

        $data = $this->json('GET', '/api/v1/analyst/TST')->json()['data'];

        $this->assertSame(10, $data['rating']['buy']);
        $this->assertSame(20, $data['rating']['sell']);
        $this->assertSame(30, $data['rating']['hold']);
        $this->assertEquals('2021-04-18', $data['rating']['date']);

        $this->assertSame(60, $data['numberOfAnalysts']);
    }

    /** @test */
    public function it_should_use_cache()
    {
        $analyst = Analyst::factory()
            ->state([
                'ticker'                => 'TST',
                'price_target_low'      => 100.28,
                'price_target_average'  => 150.331,
                'price_target_high'     => 200,
                'buy'                   => 10,
                'sell'                  => 20,
                'hold'                  => 30,
                'number_of_analysts'    => 60,
                'rating_date'           => '2021-04-18 12:33:01'
            ])
            ->create();

        $analyst->price_target_average = 1;
        $resource = new AnalystResource($analyst);

        $this->mock(CacheService::class, function (MockInterface $mock) use ($resource) {
            $mock->shouldReceive('getOrRemember')
                ->andReturn($resource);
        });

        $data = $this->json('GET', '/api/v1/analyst/TST')->json()['data'];
        $this->assertEquals(1, $data['priceTarget']['average']['raw']);
    }
}
