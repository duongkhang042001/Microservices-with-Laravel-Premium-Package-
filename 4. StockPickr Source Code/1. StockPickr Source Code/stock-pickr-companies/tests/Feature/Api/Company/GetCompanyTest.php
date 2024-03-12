<?php

namespace Tests\Feature\Api\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyPeer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCompanyTest extends TestCase
{
    use RefreshDatabase;

    private const COMPANY = [
        'ticker'                => 'TST',
        'name'                  => 'Test Inc.',
    ];

    /** @test */
    public function it_should_return_200()
    {
        Company::factory()->state($this::COMPANY)->create();

        $response = $this->json('GET', '/api/v1/companies/' . $this::COMPANY['ticker']);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_return_a_company()
    {
        Company::factory()
            ->state($this::COMPANY)
            ->create();

        $company = $this->json('GET', '/api/v1/companies/' . $this::COMPANY['ticker'])->json()['data'];

        $this->assertEquals($this::COMPANY['name'], $company['name']);
    }

    /** @test */
    public function it_should_return_404_if_company_not_found()
    {
        $response = $this->json('GET', '/api/v1/companies/' . $this::COMPANY['ticker']);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_should_return_peers_for_a_company()
    {
        $company = Company::factory()
            ->state($this::COMPANY)
            ->create();

        $peer1 = Company::factory(['ticker' => 'PEE1'])->create();
        $peer2 = Company::factory(['ticker' => 'PEE2'])->create();

        CompanyPeer::factory([
            'company_id' => $company->id,
            'peer_id' => $peer1->id,
            'ticker' => $peer1->ticker
        ])->create();
        CompanyPeer::factory([
            'company_id' => $company->id,
            'peer_id' => $peer2->id,
            'ticker' => $peer2->ticker
        ])->create();

        $company = $this->json('GET', '/api/v1/companies/' . $this::COMPANY['ticker'])->json()['data'];
        $tickers = collect($company['peers'])->pluck('ticker')->all();

        $this->assertEquals(['PEE1', 'PEE2'], $tickers);
    }
}
