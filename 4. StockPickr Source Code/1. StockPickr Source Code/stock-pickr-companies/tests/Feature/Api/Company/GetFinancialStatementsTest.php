<?php

namespace Tests\Feature\Api\Company;

use App\Models\Company\Company;
use StockPickr\Common\Services\CacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class GetFinancialStatementsTest extends TestCase
{
    use RefreshDatabase;

    private const TICKER = 'TST';

    public function setUp(): void
    {
        parent::setUp();

        Company::factory()
            ->state(['ticker' => $this::TICKER])
            ->create();
    }

    /** @test */
    public function it_should_return_200()
    {
        $response = $this->json('GET', '/api/v1/companies/' . $this::TICKER . '/financial-statements/income-statement');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_use_cache()
    {
        $this->mock(CacheService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getOrRemember')
                ->withSomeOfArgs('TST-financial-statement-income-statement')
                ->andReturn(['statement' => 'income']);
        });

        $data = $this->json('GET', '/api/v1/companies/' . $this::TICKER . '/financial-statements/income-statement');
        $this->assertEquals('income', $data['statement']);
    }

    /** @test */
    public function it_should_return_an_income_statement()
    {
        $data = $this->json('GET', '/api/v1/companies/' . $this::TICKER . '/financial-statements/income-statement')->json()['data'];
        $this->assertArrayHasKey('totalRevenue', $data);
    }

    /** @test */
    public function it_should_return_a_balance_sheet()
    {
        $data = $this->json('GET', '/api/v1/companies/' . $this::TICKER . '/financial-statements/balance-sheet')->json()['data'];
        $this->assertArrayHasKey('totalAssets', $data);
    }

    /** @test */
    public function it_should_return_a_cash_flow()
    {
        $data = $this->json('GET', '/api/v1/companies/' . $this::TICKER . '/financial-statements/cash-flow')->json()['data'];
        $this->assertArrayHasKey('operatingCashFlow', $data);
    }
}
