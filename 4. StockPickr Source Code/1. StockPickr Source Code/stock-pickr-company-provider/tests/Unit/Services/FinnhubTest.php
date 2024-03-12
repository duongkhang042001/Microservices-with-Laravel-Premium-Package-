<?php

namespace Tests\Unit\Services;

use App\Services\Finnhub;
use App\Services\Http\HttpClient;
use App\Services\Mapper\FinnhubMapper;
use Tests\TestCase;

class FinnhubTest extends TestCase
{
    /** @test */
    public function it_should_return_a_company()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/profile', ['symbol' => 'AAPL'])
                ->andReturns([
                    'name'                  => 'Apple',
                    'ticker'                => 'AAPL',
                    'description'           => 'Apple Inc',
                    'gind'                  => 'Technology',
                    'gsector'               => 'Hardware',
                    'employeeTotal'         => 130000,
                    'marketCapitalization'  => 10000.726,
                    'shareOutstanding'      => 4200.1189
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/executive', ['symbol' => 'AAPL'])
                ->andReturns([
                    'executive' => [
                        ['name' => 'Not CEO John', 'position' => 'CFO'],
                        ['name' => 'CEO Jack', 'position' => 'Chief Executive Officer'],
                        ['name' => 'Not CEO Gabe', 'position' => 'CTO']
                    ]
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/quote', ['symbol' => 'AAPL'])
                ->andReturns([
                    'c' => 122.21
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/metric', ['symbol' => 'AAPL', 'metric' => 'price'])
                ->andReturns([
                    'metric'  => [
                        'beta'  => 1.31
                    ]
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/peers', ['symbol' => 'AAPL'])
                ->andReturns([
                    'FB', 'MSFT', 'AMZN'
                ]);



            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'bs', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(2400, 2017),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1000, 2010)
                    ]
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'ic', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(2400, 2017),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1000, 2010)
                    ]
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'cf', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(2400, 2017),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1000, 2010)
                    ]
                ]);
        });

        $this->partialMock(FinnhubMapper::class, function ($mock) {
            $returnValue = [];
            $mock
                ->shouldReceive('mapStatement')
                ->times(3)
                ->withArgs(function (array $balanceSheets, string $type) use (&$returnValue) {
                    if (count($balanceSheets) === 5) {
                        $returnValue = $balanceSheets;
                        return true;
                    }

                    return false;
                })
                ->andReturnUsing(function (array $balanceSheets, string $type) {
                    return $balanceSheets;
                });
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $company = $finnhub->getCompany('AAPL');

        $this->assertEquals('APPLE', $company->name);
        $this->assertEquals('AAPL', $company->ticker);
        $this->assertEquals('Apple Inc', $company->description);
        $this->assertEquals('Technology', $company->industry);
        $this->assertEquals('Hardware', $company->sector);
        $this->assertEquals('CEO Jack', $company->ceo);

        $this->assertEquals(collect(['FB', 'MSFT', 'AMZN']), $company->peers);

        $this->assertCount(5, $company->getBalanceSheets());
        $years = collect($company->getBalanceSheets())->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);

        $this->assertCount(5, $company->getIncomeStatements());
        $years = collect($company->getIncomeStatements())->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);

        $this->assertCount(5, $company->getCashFlows());
        $years = collect($company->getCashFlows())->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);
    }

    /** @test */
    public function it_returns_the_last_5_balance_sheets_even_if_finnhub_gives_in_reversed_order()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'bs', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(1000, 2010),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2400, 2017)
                    ]
                ]);
        });

        $this->mock(FinnhubMapper::class, function ($mock) {
            $returnValue = [];
            $mock
                ->shouldReceive('mapStatement')
                ->withArgs(function (array $balanceSheets, string $type) use (&$returnValue) {
                    if (count($balanceSheets) === 5) {
                        $returnValue = $balanceSheets;
                        return true;
                    }

                    return false;
                })
                ->andReturnUsing(function (array $balanceSheets, string $type) {
                    return $balanceSheets;
                });
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $balanceSheets = $finnhub->getBalanceSheets('AAPL');

        $this->assertCount(5, $balanceSheets);

        $years = collect($balanceSheets)->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);
    }

    /** @test */
    public function it_returns_the_last_5_income_statements_even_if_finnhub_gives_in_reversed_order()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'ic', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(1000, 2010),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2400, 2017)
                    ]
                ]);
        });

        $this->mock(FinnhubMapper::class, function ($mock) {
            $returnValue = [];
            $mock
                ->shouldReceive('mapStatement')
                ->withArgs(function (array $balanceSheets, string $type) use (&$returnValue) {
                    if (count($balanceSheets) === 5) {
                        $returnValue = $balanceSheets;
                        return true;
                    }

                    return false;
                })
                ->andReturnUsing(function (array $balanceSheets, string $type) {
                    return $balanceSheets;
                });
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $incomeStatements = $finnhub->getIncomeStatements('AAPL');

        $this->assertCount(5, $incomeStatements);

        $years = collect($incomeStatements)->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);
    }

    /** @test */
    public function it_returns_the_last_5_cash_flows_even_if_finnhub_gives_in_reversed_order()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'cf', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        $this->getFinancialStatement(1000, 2010),
                        $this->getFinancialStatement(1200, 2011),
                        $this->getFinancialStatement(1400, 2012),
                        $this->getFinancialStatement(1600, 2013),
                        $this->getFinancialStatement(1800, 2014),
                        $this->getFinancialStatement(2000, 2015),
                        $this->getFinancialStatement(2200, 2016),
                        $this->getFinancialStatement(2400, 2017)
                    ]
                ]);
        });

        $this->mock(FinnhubMapper::class, function ($mock) {
            $returnValue = [];
            $mock
                ->shouldReceive('mapStatement')
                ->withArgs(function (array $balanceSheets, string $type) use (&$returnValue) {
                    if (count($balanceSheets) === 5) {
                        $returnValue = $balanceSheets;
                        return true;
                    }

                    return false;
                })
                ->andReturnUsing(function (array $balanceSheets, string $type) {
                    return $balanceSheets;
                });
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $cashFlows = $finnhub->getCashFlows('AAPL');

        $this->assertCount(5, $cashFlows);

        $years = collect($cashFlows)->pluck('year')->all();
        $this->assertEquals([2017, 2016, 2015, 2014, 2013], $years);
    }

    /** @test */
    public function it_returns_the_current_price_as_0_if_finnhub_gives_empty_response()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');
            $mock
                ->shouldReceive('get')
                ->with('/quote', ['symbol' => 'AAPL'])
                ->andReturns([
                    'c' => null,
                    'o' => 100,
                    'l' => 80
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $price = $this->invokeMethod($finnhub, 'getCurrentPrice', ['AAPL']);

        $this->assertEquals(0, $price);
    }

    /** @test */
    public function it_returns_the_beta_as_null_if_finnhub_gives_empty_response()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');
            $mock
                ->shouldReceive('get')
                ->with('/stock/metric', ['symbol' => 'AAPL', 'metric' => 'price'])
                ->andReturns([
                    'metric' => [
                        'beta'  => null
                    ]
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $beta = $this->invokeMethod($finnhub, 'getBeta', ['AAPL']);

        $this->assertEquals(null, $beta);
    }

    /** @test */
    public function it_only_returns_most_recent_recommendation()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');
            $mock
                ->shouldReceive('get')
                ->with('/stock/recommendation', ['symbol' => 'AAPL'])
                ->andReturns([
                    ['buy' => 8, 'period' => '2020-11-01'],
                    ['buy' => 10, 'strongBuy' => 2, 'sell' => 3, 'strongSell' => 2, 'hold' => 1, 'period' => '2020-12-01'],
                    ['buy' => 5, 'period' => '2020-10-01'],
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $recommendation = $this->invokeMethod($finnhub, 'getMostRecentRecommendation', ['AAPL']);

        $this->assertEquals([
            'strongBuy'     => 2,
            'buy'           => 10,
            'strongSell'    => 2,
            'sell'          => 3,
            'hold'          => 1,
            'period'        => '2020-12-01',

        ], $recommendation);
    }

    /** @test */
    public function it_returns_empty_array_if_response_is_empty()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');
            $mock
                ->shouldReceive('get')
                ->with('/stock/recommendation', ['symbol' => 'AAPL'])
                ->andReturns([]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $recommendation = $this->invokeMethod($finnhub, 'getMostRecentRecommendation', ['AAPL']);

        $this->assertEquals([], $recommendation);
    }

    /** @test */
    public function it_returns_available_tickers()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/symbol', ['exchange' => 'US'])
                ->andReturns([
                    ['type' => 'Common Stock', 'symbol' => 'AAPL-symbol', 'displaySymbol' => 'AAPL-display-symbol', 'mic' => 'XNAS'],
                    ['type' => 'Common Stock', 'symbol' => 'SQ-symbol',   'displaySymbol' => 'SQ-display-symbol',   'mic' => 'XNAS'],
                    ['type' => 'Other',        'symbol' => 'BOND-symbol', 'displaySymbol' => 'BOND-display-symbol', 'mic' => 'XNAS'],
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $tickers = $finnhub->getAvailableTickers();

        $this->assertCount(2, $tickers);
        $this->assertContains('AAPL-display-symbol', $tickers);
        $this->assertContains('SQ-display-symbol', $tickers);

        $this->assertNotContains('BOND-display-symbol', $tickers);
    }

    /** @test */
    public function regression_it_can_fallback_to_cash_equivalents_if_no_cash_returned()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'AAPL', 'statement' => 'bs', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        [
                            'cashEquivalents'   => 100,
                            'year'              => 2020
                        ],
                        [
                            'cash'              => 120,
                            'cashEquivalents'   => 100,
                            'year'              => 2019
                        ]
                    ]
                ]);
        });

        $finnhub = resolve(Finnhub::class);
        $balanceSheets = $finnhub->getBalanceSheets('AAPL');

        $this->assertEquals(100, $balanceSheets['2020']['currentCash']);
        $this->assertEquals(120, $balanceSheets['2019']['currentCash']);
    }

    /** @test */
    public function regression_it_can_fallback_to_ebit_as_operating_income_if_no_gross_profit()
    {
        /**
         * MA esetén pl nincs Gross profit, így nem tud gross - operating expese számolni
         */
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/financials', ['symbol' => 'MA', 'statement' => 'ic', 'freq' => 'annual'])
                ->andReturns([
                    'financials' => [
                        [
                            'grossIncome'           => 100,
                            'totalOperatingExpense' => 20,
                            'ebit'                  => 6000,
                            'year'                  => 2020
                        ],
                        [
                            'totalOperatingExpense' => 30,
                            'ebit'                  => 70,
                            'year'                  => 2019
                        ]
                    ]
                ]);
        });

        $finnhub = resolve(Finnhub::class);
        $balanceSheets = $finnhub->getIncomeStatements('MA');

        $this->assertEquals(80, $balanceSheets['2020']['operatingIncome']);
        $this->assertEquals(70, $balanceSheets['2019']['operatingIncome']);
    }

    /** @test */
    public function it_should_exclude_otc_stocks()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/symbol', ['exchange' => 'US'])
                ->andReturns([
                    ['type' => 'Common Stock', 'symbol' => 'AAPL-symbol', 'displaySymbol' => 'AAPL-display-symbol', 'mic' => 'XNAS'],
                    ['type' => 'Common Stock', 'symbol' => 'SQ-symbol',   'displaySymbol' => 'SQ-display-symbol',   'mic' => 'XNAS'],
                    ['type' => 'Common Stock', 'symbol' => 'OTC-1',       'displaySymbol' => 'OTC-1',               'mic' => 'OTCM'],
                    ['type' => 'Common Stock', 'symbol' => 'OTC-2',       'displaySymbol' => 'OTC-2',               'mic' => 'OTCB'],
                    ['type' => 'Common Stock', 'symbol' => 'OTC-3',       'displaySymbol' => 'OTC-3',               'mic' => 'OOTC'],
                    ['type' => 'Common Stock', 'symbol' => 'OTC-4',       'displaySymbol' => 'OTC-4',               'mic' => 'OOTCM'],
                    ['type' => 'Common Stock', 'symbol' => 'NO-MIC',      'displaySymbol' => 'NO-MIC'],
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $tickers = $finnhub->getAvailableTickers();

        $this->assertCount(2, $tickers);
        $this->assertContains('AAPL-display-symbol', $tickers);
        $this->assertContains('SQ-display-symbol', $tickers);

        $this->assertNotContains('OTC-1', $tickers);
        $this->assertNotContains('OTC-2', $tickers);
        $this->assertNotContains('OTC-3', $tickers);
        $this->assertNotContains('OTC-4', $tickers);
        $this->assertNotContains('NO-MIC', $tickers);
    }

    /** @test */
    public function it_should_not_return_itself_as_a_peer()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');
            $mock
                ->shouldReceive('get')
                ->with('/stock/peers', ['symbol' => 'AAPL'])
                ->andReturns([
                    'AAPL', 'FB', 'MSFT', 'AMZN'
                ]);
        });

        $finnhub = resolve(Finnhub::class);
        $peers = $finnhub->getPeers('AAPL', 5);

        $this->assertEquals(['FB', 'MSFT', 'AMZN'], $peers);
    }

    /** @test */
    public function it_should_replace_ord_with_inc_in_company_name()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/profile', ['symbol' => 'AAPL'])
                ->andReturns([
                    'name'          => 'Apple Ord',
                    'ticker'        => 'AAPL',
                    'description'   => 'Apple Inc',
                    'gind'          => 'Technology',
                    'gsector'       => 'Hardware',
                    'employeeTotal' => 130000
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/executive', ['symbol' => 'AAPL'])
                ->once()
                ->andReturns([
                    'executive' => [
                        ['name' => 'CEO Jack', 'position' => 'Chief Executive Officer'],
                    ]
                ]);
        });

        /** @var Finnhub */
        $finnhub = resolve(Finnhub::class);
        $company = $this->invokeMethod($finnhub, 'getCompanyData', ['AAPL']);

        $this->assertEquals('APPLE INC', $company['name']);
    }

    /** @test */
    public function it_should_not_replace_ord_as_a_part_of_the_company_name()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/profile', ['symbol' => 'AAPL'])
                ->andReturns([
                    'name'          => 'Some Ordinary Company',
                    'ticker'        => 'AAPL',
                    'description'   => 'Apple Inc',
                    'gind'          => 'Technology',
                    'gsector'       => 'Hardware',
                    'employeeTotal' => 130000
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/executive', ['symbol' => 'AAPL'])
                ->once()
                ->andReturns([
                    'executive' => [
                        ['name' => 'CEO Jack', 'position' => 'Chief Executive Officer'],
                    ]
                ]);
        });

        /** @var Finnhub */
        $finnhub = resolve(Finnhub::class);
        $company = $this->invokeMethod($finnhub, 'getCompanyData', ['AAPL']);

        $this->assertEquals('SOME ORDINARY COMPANY', $company['name']);
    }

    /** @test */
    public function it_should_exclude_stocks_with_a_dot_in_its_name()
    {
        // XY.TO, PSA.PRN stb

        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/symbol', ['exchange' => 'US'])
                ->andReturns([
                    ['type' => 'Common Stock', 'symbol' => 'AAPL',      'displaySymbol' => 'AAPL',      'mic' => 'US'],
                    ['type' => 'Common Stock', 'symbol' => 'XY.TO',     'displaySymbol' => 'XY.TO',     'mic' => 'US'],
                    ['type' => 'Common Stock', 'symbol' => 'PSA.PRN',   'displaySymbol' => 'PSA.PRN',   'mic' => 'US'],
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $tickers = $finnhub->getAvailableTickers();

        $this->assertCount(1, $tickers);
        $this->assertContains('AAPL', $tickers);
    }

    /** @test */
    public function it_should_return_share_data()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/profile', ['symbol' => 'AAPL'])
                ->andReturns([
                    'name'                  => 'Apple',
                    'ticker'                => 'AAPL',
                    'description'           => 'Apple Inc',
                    'gind'                  => 'Technology',
                    'gsector'               => 'Hardware',
                    'employeeTotal'         => 130000,
                    'marketCapitalization'  => 10000.726,
                    'shareOutstanding'      => 4200.1189
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/executive', ['symbol' => 'AAPL'])
                ->andReturns([
                    'executive' => [
                        ['name' => 'Not CEO John', 'position' => 'CFO'],
                        ['name' => 'CEO Jack', 'position' => 'Chief Executive Officer'],
                        ['name' => 'Not CEO Gabe', 'position' => 'CTO']
                    ]
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/quote', ['symbol' => 'AAPL'])
                ->andReturns([
                    'c' => 122.21
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/metric', ['symbol' => 'AAPL', 'metric' => 'price'])
                ->andReturns([
                    'metric'  => [
                        'beta'  => 1.31
                    ]
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $shareData = $finnhub->getShareData('AAPL');

        $this->assertEquals(122.21, $shareData->price);
        $this->assertEquals(10000.726, $shareData->marketCap);
        $this->assertEquals(1.31, $shareData->beta);
        $this->assertEquals(4200.1189, $shareData->sharesOutstanding);
    }

    /** @test */
    public function it_should_return_analyst()
    {
        $this->mock(HttpClient::class, function ($mock) {
            $mock->shouldReceive('setBaseUrl');
            $mock->shouldReceive('setApiToken');

            $mock
                ->shouldReceive('get')
                ->with('/stock/recommendation', ['symbol' => 'AAPL'])
                ->andReturns([
                    ['buy' => 10, 'strongBuy' => 2, 'sell' => 3, 'strongSell' => 2, 'hold' => 1, 'period' => '2020-12-01'],
                    ['buy' => 8, 'period' => '2020-11-01'],
                    ['buy' => 5, 'period' => '2020-10-01']
                ]);

            $mock
                ->shouldReceive('get')
                ->with('/stock/price-target', ['symbol' => 'AAPL'])
                ->andReturns([
                    'targetMean'    => 15.231,
                    'targetLow'     => 8.7,
                    'targetHigh'    => 22.34
                ]);
        });

        /** @var Finnhub $finnhub */
        $finnhub = resolve(Finnhub::class);
        $analyst = $finnhub->getAnalyst('AAPL');

        $this->assertEquals([
            'priceTarget' => [
                'low'       => 8.7,
                'average'   => 15.231,
                'high'      => 22.34
            ],
            'rating' => [
                'buy'   => 12,
                'hold'  => 1,
                'sell'  => 5,
                'date'  => '2020-12-01'
            ]
        ], $analyst->toArray());
    }

    // -------- Helpers --------

    protected function getFinancialStatement(float $totalAssets, int $year): array
    {
        return [
            'totalAssets'   => $totalAssets,
            'year'          => $year
        ];
    }
}
