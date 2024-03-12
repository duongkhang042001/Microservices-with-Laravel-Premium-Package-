<?php

namespace Tests\Feature\Api;

use App\ChartConfigs\EpsChartConfig;
use App\Models\Chart;
use StockPickr\Common\Services\CacheService;
use App\Services\ChartService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Mockery\MockInterface;

class GetChartGroupApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_404_if_chart_not_found()
    {
        $response = $this->json('GET', '/api/v1/charts/AAPL/summary');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_404_if_non_existing_chart_is_requested()
    {
        $response = $this->json('GET', '/api/v1/charts/AAPL/foo');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_200()
    {
        $this->createAllCharts('TST');

        $response = $this->json('GET', '/api/v1/charts/TST/summary');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_years_as_labels()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $labels = $charts['eps']['data']['labels'];
        $this->assertEquals([2020, 2019, 2018, 2017], $labels);
    }

    /** @test */
    public function it_should_return_data_as_datasets()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $datasets = $charts['eps']['data']['datasets'];
        $this->assertEquals([
            [
                'label' => 'EPS',
                'data'  => [
                    133, 121, 110, 100
                ],
                'fill'  => false
            ],
        ], $datasets);
    }

    /** @test */
    public function it_should_return_config_from_chart()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $this->assertEquals((new EpsChartConfig)->config(), $charts['eps']['config']);
    }

    /** @test */
    public function it_should_return_the_title_of_the_chart()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $this->assertEquals('Earnings Per Share', $charts['eps']['config']['title']['text']);
    }

    /** @test */
    public function it_should_return_the_type_of_the_chart()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $this->assertEquals(ChartService::TYPE_BAR, $charts['eps']['type']);
    }

    /** @test */
    public function it_should_return_the_normalizer_of_the_chart()
    {
        Chart::factory()
            ->state([
                'ticker'    => 'TST',
                'chart'     => 'eps',
                'data'      => [
                    [
                        'label' => 'EPS',
                        'data'  => [
                            133, 121, 110, 100
                        ],
                        'fill'  => false
                    ],
                ]
            ])->create();

        $charts = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $this->assertEquals(ChartService::NORMALIZER_MONEY, $charts['eps']['normalizer']);
    }

    /** @test */
    public function it_should_use_cache()
    {
        $this->mock(CacheService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getOrRemember')
                ->andReturn('result from cache');
        });

        $result = $this->json('GET', '/api/v1/charts/TST/income-statement')->json()['data'];

        $this->assertEquals('result from cache', $result);
    }
}
