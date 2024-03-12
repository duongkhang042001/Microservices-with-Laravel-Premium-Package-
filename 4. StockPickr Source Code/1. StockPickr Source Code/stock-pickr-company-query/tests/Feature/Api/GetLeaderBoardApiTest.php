<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class GetLeaderBoardApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_200()
    {
        $response = $this->json('GET', '/api/v1/queries/leaderboard?limit=12&offset=0');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_422_if_limit_missing()
    {
        $response = $this->json('GET', '/api/v1/queries/leaderboard?offset=0');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_422_if_offset_missing()
    {
        $response = $this->json('GET', '/api/v1/queries/leaderboard?limit=12');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_return_the_top_companies_based_on_position()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1],
                ['ticker' => 'TST2', 'position' => 2],
                ['ticker' => 'TST3', 'position' => 3],
                ['ticker' => 'TST4', 'position' => 4],
                ['ticker' => 'TST5', 'position' => 5],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=3&offset=0')->json('data');
        $tickers = collect($data)->pluck('ticker')->all();

        $this->assertEquals(['TST1', 'TST2', 'TST3'], $tickers);
    }

    /** @test */
    public function it_should_exclude_companies_with_null_position()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1],
                ['ticker' => 'TST2', 'position' => null],
                ['ticker' => 'TST3', 'position' => 2],
                ['ticker' => 'TST4', 'position' => null],
                ['ticker' => 'TST5', 'position' => null],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=3&offset=0')->json('data');
        $tickers = collect($data)->pluck('ticker')->all();

        $this->assertEquals(['TST1', 'TST3'], $tickers);
    }

    /** @test */
    public function it_should_return_company_name()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'name' => 'Test 1', 'position' => 1],
                ['ticker' => 'TST2', 'name' => 'Test 2', 'position' => 2],
                ['ticker' => 'TST3', 'position' => 3],
                ['ticker' => 'TST4', 'position' => 4],
                ['ticker' => 'TST5', 'position' => 5],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=2&offset=0')->json('data');
        $names = collect($data)->pluck('name')->all();

        $this->assertEquals(['Test 1', 'Test 2'], $names);
    }

    /** @test */
    public function it_should_return_company_position()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1],
                ['ticker' => 'TST2', 'position' => 2],
                ['ticker' => 'TST3', 'position' => 3],
                ['ticker' => 'TST4', 'position' => 4],
                ['ticker' => 'TST5', 'position' => 5],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=5&offset=0')->json('data');
        $positions = collect($data)->pluck('position')->all();

        $this->assertEquals([1, 2, 3, 4, 5], $positions);
    }

    /** @test */
    public function it_should_return_company_scores()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1, 'total_scores' => 50],
                ['ticker' => 'TST2', 'position' => 2, 'total_scores' => 40],
                ['ticker' => 'TST3', 'position' => 3, 'total_scores' => 30],
                ['ticker' => 'TST4', 'position' => 4, 'total_scores' => 20],
                ['ticker' => 'TST5', 'position' => 5, 'total_scores' => 10],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=5&offset=0')->json('data');
        $scores = collect($data)->pluck('totalScores')->all();

        $this->assertEquals([50, 40, 30, 20, 10], $scores);
    }

    /** @test */
    public function it_should_return_company_score_percents()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1, 'total_score_percent' => 0.50],
                ['ticker' => 'TST2', 'position' => 2, 'total_score_percent' => 0.40],
                ['ticker' => 'TST3', 'position' => 3, 'total_score_percent' => 0.30],
                ['ticker' => 'TST4', 'position' => 4, 'total_score_percent' => 0.20],
                ['ticker' => 'TST5', 'position' => 5, 'total_score_percent' => 0.10],
            ))
            ->count(5)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=5&offset=0')->json('data');
        $scores = collect($data)->pluck('totalScorePercent')->all();

        $this->assertEquals(['50%', '40%', '30%', '20%', '10%'], $scores);
    }

    /** @test */
    public function it_should_use_offset()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'TST1', 'position' => 1],
                ['ticker' => 'TST2', 'position' => 2],
                ['ticker' => 'TST3', 'position' => 3],
                ['ticker' => 'TST4', 'position' => 4],
                ['ticker' => 'TST5', 'position' => 5],
                ['ticker' => 'TST6', 'position' => 6],
            ))
            ->count(6)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/leaderboard?limit=3&offset=1')->json('data');
        $tickers = collect($data)->pluck('ticker')->all();

        $this->assertEquals(['TST4', 'TST5', 'TST6'], $tickers);
    }
}
