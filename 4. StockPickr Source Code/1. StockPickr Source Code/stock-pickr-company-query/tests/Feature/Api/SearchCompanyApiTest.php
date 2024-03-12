<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SearchCompanyApiTest extends TestCase
{
    use RefreshDatabase;

    private const COMPANIES = [

    ];

    private function createCompanies()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'AAPL',  'name' => 'Apple Inc.',        'position' => 1],
                ['ticker' => 'TSLA',  'name' => 'Tesla Motors Inc.', 'position' => 2],
                ['ticker' => 'FB',    'name' => 'Facebook Inc.',     'position' => 3],
                ['ticker' => 'FP',    'name' => 'Facepalm Inc.',     'position' => 4],
                ['ticker' => 'AAPL2', 'name' => 'Fake Apple Inc.',   'position' => 5],
            ))
            ->count(5)
            ->create();
    }

    /** @test */
    public function it_should_return_200()
    {
        $this->createCompanies();
        $response = $this->json('GET', '/api/v1/queries/search?q=aapl');
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function it_should_return_422_if_no_query()
    {
        $this->createCompanies();
        $response = $this->json('GET', '/api/v1/queries/search');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_search_companies_by_ticker()
    {
        $this->createCompanies();
        $companies = $this->json('GET', '/api/v1/queries/search?q=aapl')->json('data');

        $this->assertCount(2, $companies);
        $this->assertEquals(['AAPL', 'AAPL2'], collect($companies)->pluck('ticker')->all());
        $this->assertEquals(['Apple Inc.', 'Fake Apple Inc.'], collect($companies)->pluck('name')->all());
    }

    /** @test */
    public function it_should_search_companies_by_name()
    {
        $this->createCompanies();
        $companies = $this->json('GET', '/api/v1/queries/search?q=FACE')->json('data');

        $this->assertCount(2, $companies);
        $this->assertEquals(['FB', 'FP'], collect($companies)->pluck('ticker')->all());
        $this->assertEquals(['Facebook Inc.', 'Facepalm Inc.'], collect($companies)->pluck('name')->all());
    }

    /** @test */
    public function it_should_return_company_name()
    {
        $this->createCompanies();
        $companies = $this->json('GET', '/api/v1/queries/search?q=FACE')->json('data');
        $names = collect($companies)->pluck('name')->all();

        $this->assertEquals(['Facebook Inc.', 'Facepalm Inc.'], $names);
    }

    /** @test */
    public function it_should_return_company_full_name()
    {
        $this->createCompanies();
        $companies = $this->json('GET', '/api/v1/queries/search?q=FACEbook')->json('data');
        $company = collect($companies)->first();

        $this->assertEquals('FB - Facebook Inc.', $company['fullName']);
    }

    /** @test */
    public function it_should_return_company_position()
    {
        $this->createCompanies();
        $data = $this->json('GET', '/api/v1/queries/search?q=FACE')->json('data');
        $positions = collect($data)->pluck('position')->all();

        $this->assertEquals([3, 4], $positions);
    }

    /** @test */
    public function it_should_return_company_scores()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'FB', 'position' => 1, 'total_scores' => 50],
                ['ticker' => 'FBA', 'position' => 2, 'total_scores' => 40],
                ['ticker' => 'FBAV', 'position' => 3, 'total_scores' => 30],
            ))
            ->count(3)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/search?q=FB')->json('data');
        $scores = collect($data)->pluck('totalScores')->all();

        $this->assertEquals([50, 40, 30], $scores);
    }

    /** @test */
    public function it_should_return_company_score_percents()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'AAPL', 'position' => 1, 'total_score_percent' => 0.50],
                ['ticker' => 'AABV', 'position' => 2, 'total_score_percent' => 0.40],
            ))
            ->count(2)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/search?q=aa')->json('data');
        $scores = collect($data)->pluck('totalScorePercent')->all();

        $this->assertEquals(['50%', '40%'], $scores);
    }

    /** @test */
    public function it_should_exclude_companies_with_null_position()
    {
        Company::factory()
            ->state(new Sequence(
                ['ticker' => 'AB',    'position' => 1],
                ['ticker' => 'ABC',   'position' => 2],
                ['ticker' => 'ABCD',  'position' => null],
                ['ticker' => 'ABCDE', 'position' => null],
            ))
            ->count(4)
            ->create();

        $data = $this->json('GET', '/api/v1/queries/search?q=ab')->json('data');
        $tickers = collect($data)->pluck('ticker')->all();

        $this->assertEquals(['AB', 'ABC'], $tickers);
    }

    /** @test */
    public function it_should_only_return_a_limited_number_of_companies()
    {
        foreach (range(1, 100) as $i) {
            Company::factory()
                ->state([
                    'name' => 'Test',
                    'position' => $i
                ])
                ->create();
        }

        $companies = $this->json('GET', '/api/v1/queries/search?q=test')->json('data');
        $this->assertCount(CompanyRepository::NUMBER_OF_SEARCH_RESULTS, $companies);
    }

    /** @test */
    public function it_uses_cache()
    {
        $this->json('GET', '/api/v1?search?q=face');
        $this->json('GET', '/api/v1?search?q=face');

        Cache::shouldReceive('get')
            ->with('company-search-face');

        Cache::shouldReceive('remember')
            ->with('company-search-face', 10 * 60, \Closure::class);
    }
}
