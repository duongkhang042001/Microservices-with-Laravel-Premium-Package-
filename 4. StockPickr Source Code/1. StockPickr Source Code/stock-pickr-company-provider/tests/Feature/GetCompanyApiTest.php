<?php

namespace Tests\Feature;

use App\Services\MarketDataProvider;
use Mockery\MockInterface;
use Tests\TestCase;

class GetCompanyApiTest extends TestCase
{
    /** @test */
    public function it_should_return_200()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000'
                ]));
        });

        $response = $this->json('GET', '/api/v1/company-provider/companies/TST');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_return_a_company_base_data()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000'
                ]));
        });

        $company = $this->json('GET', '/api/v1/company-provider/companies/TST')->json()['data'];

        $this->assertEquals('TST', $company['ticker']);
        $this->assertEquals('Test Inc.', $company['name']);
        $this->assertEquals('Description', $company['description']);
        $this->assertEquals('Technology Hardware', $company['industry']);
        $this->assertEquals('Information Technology', $company['sector']);
        $this->assertEquals('John Doe', $company['ceo']);
        $this->assertSame(147000, $company['employees']);
    }

    /** @test */
    public function it_should_return_a_company_with_income_statements()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000',
                    'incomeStatements'  => [
                        '2020' => [
                            'total_revenue'     => 100
                        ]
                    ],
                ]));
        });

        $company = $this->json('GET', '/api/v1/company-provider/companies/TST')->json()['data'];

        $this->assertEquals([
            '2020' => [
                'total_revenue'     => 100
            ]
        ], $company['financialStatements']['incomeStatements']);
    }

    /** @test */
    public function it_should_return_a_company_with_balance_sheets()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000',
                    'balanceSheets'     => [
                        '2020' => [
                            'total_assets'     => 100
                        ]
                    ]
                ]));
        });

        $company = $this->json('GET', '/api/v1/company-provider/companies/TST')->json()['data'];

        $this->assertEquals([
            '2020' => [
                'total_assets'     => 100
            ]
        ], $company['financialStatements']['balanceSheets']);
    }

    /** @test */
    public function it_should_return_a_company_with_cash_flows()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000',
                    'cashFlows'         => [
                        '2020' => [
                            'cash_from_operation'     => 100
                        ]
                    ]
                ]));
        });

        $company = $this->json('GET', '/api/v1/company-provider/companies/TST')->json()['data'];

        $this->assertEquals([
            '2020' => [
                'cash_from_operation'     => 100
            ]
        ], $company['financialStatements']['cashFlows']);
    }

    /** @test */
    public function it_should_return_a_company_with_peers()
    {
        $this->mock(MarketDataProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('getCompany')
                ->with('TST')
                ->andReturns($this->createUpsertCompanyContainer([
                    'ticker'        => 'TST',
                    'name'          => 'Test Inc.',
                    'description'   => 'Description',
                    'industry'      => 'Technology Hardware',
                    'sector'        => 'Information Technology',
                    'ceo'           => 'John Doe',
                    'employees'     => '147000',
                    'peers'         => ['TST1', 'TST2'],
                ]));
        });

        $company = $this->json('GET', '/api/v1/company-provider/companies/TST')->json()['data'];

        $this->assertEquals(['TST1', 'TST2'], $company['peers']);
    }
}
