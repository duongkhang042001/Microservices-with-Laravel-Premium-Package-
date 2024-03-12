<?php

namespace Tests\Unit;

use App\Models\CompanyUpdate;
use App\Models\DeniedCompany;
use App\Services\CompanyProviderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CompanyProviderServiceTest extends TestCase
{
    use RefreshDatabase;

    private string $url;
    private CompanyProviderService $companyProvider;

    public function setUp(): void
    {
        parent::setUp();

        $this->url = config('services.company-provider.url');
        $this->companyProvider = resolve(CompanyProviderService::class);
    }

    /** @test */
    public function it_should_return_a_company()
    {
        Http::fake([
            $this->url . 'companies/TST' => Http::response([
                'data' => [
                    'ticker' => 'TST',
                    'name'   => 'Test Inc.',
                    'sector' => 'Tech',
                    'financialStatements' => [
                        'incomeStatements'  => [],
                        'balanceSheets'     => [],
                        'cashFlows'         => [],
                    ]
                ]
            ])
        ]);

        $company = $this->companyProvider->getCompany('TST');
        $this->assertEquals('TST', $company->ticker);
    }

    /** @test */
    public function it_should_return_available_tickers()
    {
        Http::fake([
            $this->url . 'available-tickers' => Http::response([
                'data' => ['TST1', 'TST2']
            ])
        ]);

        $tickers = $this->companyProvider->getAvailableTickers();
        $this->assertEquals(['TST1', 'TST2'], $tickers->all());
    }

    /** @test */
    public function it_should_return_share_data_for_a_company()
    {
        Http::fake([
            $this->url . 'companies/TST/share-data' => Http::response([
                'data' => [
                    'price' => 110
                ]
            ])
        ]);

        $shareData = $this->companyProvider->getShareData('TST');
        $this->assertEquals(110, $shareData->price);
    }

    /** @test */
    public function it_should_return_analyst_for_a_company()
    {
        Http::fake([
            $this->url . 'companies/TST/analyst' => Http::response([
                'data' => [
                    'priceTarget' => [
                        'average' => 2
                    ],
                    'rating' => [
                        'buy' => 10
                    ]
                ]
            ])
        ]);

        $analyst = $this->companyProvider->getAnalyst('TST');
        $this->assertEquals(2, $analyst->getPriceTargetAverage());
        $this->assertEquals(10, $analyst->getBuy());
    }

    /** @test */
    public function it_should_return_next_company_for_create()
    {
        Http::fake([
            $this->url . 'available-tickers' => Http::response([
                'data' => ['TST1', 'TST2', 'TST3']
            ]),
            $this->url . 'companies/TST3' => Http::response([
                'data' => [
                    'ticker' => 'TST3',
                    'name'  => 'Test Inc.',
                    'sector' => 'Tech',
                    'financialStatements' => [
                        'incomeStatements'  => [],
                        'balanceSheets'     => [],
                        'cashFlows'         => [],
                    ]
                ]
            ])
        ]);

        DeniedCompany::factory()
            ->state([
                'ticker'    => 'TST1'
            ])
            ->create();

        CompanyUpdate::factory()
            ->state([
                'ticker'    => 'TST2'
            ])
            ->create();

        $data = $this->companyProvider->getNextCompanyForCreate();
        $this->assertEquals('TST3', $data['company']->ticker);
    }

    /** @test */
    public function it_should_return_next_company_for_update()
    {
        Http::fake([
            $this->url . 'companies/TST1' => Http::response([
                'data' => [
                    'ticker' => 'TST1',
                    'name'   => 'Test Inc.',
                    'sector' => 'Tech',
                    'financialStatements' => [
                        'incomeStatements'  => [],
                        'balanceSheets'     => [],
                        'cashFlows'         => [],
                    ]
                ]
            ])
        ]);

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST1',
                'financials_updated_at'     => now()->subWeek()
            ])
            ->create();

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST2',
                'financials_updated_at'     => now()->subDays(3)
            ])
            ->create();

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST3',
                'financials_updated_at'     => now()->subDay()
            ])
            ->create();

        $company = $this->companyProvider->getNextCompanyForUpdate();
        $this->assertEquals('TST1', $company->ticker);
    }

    /** @test */
    public function it_should_return_next_company_for_share_data_update()
    {
        Http::fake([
            $this->url . 'companies/TST1' => Http::response([
                'data' => [
                    'ticker' => 'TST1',
                    'name'  => 'Test Inc.'
                ]
            ])
        ]);

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST1',
                'market_data_updated_at'    => now()->subWeek()
            ])
            ->create();

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST2',
                'market_data_updated_at'    => now()->subDays(3)
            ])
            ->create();

        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST3',
                'market_data_updated_at'    => now()->subDay()
            ])
            ->create();

        $ticker = $this->companyProvider->getNextTickerForShareDataUpdate();
        $this->assertEquals('TST1', $ticker);
    }

    /** @test */
    public function it_should_throw_an_exception_if_nothing_to_update()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->companyProvider->getNextTickerForShareDataUpdate();
    }

    /** @test */
    public function it_should_throw_an_exception_for_update_if_create_was_failed()
    {
        CompanyUpdate::factory()
            ->state([
                'ticker'                    => 'TST',
                'market_data_updated_at'    => now(),
                'financials_updated_at'     => null     // Tehát nem sikerült a company:create
            ]);

        $this->expectException(ModelNotFoundException::class);
        $this->companyProvider->getNextCompanyForUpdate();
    }
}
