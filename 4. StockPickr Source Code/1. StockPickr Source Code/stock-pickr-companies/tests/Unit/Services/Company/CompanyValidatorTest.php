<?php

namespace Tests\Unit\Services\Company;

use App\Events\Company\UpsertCompany;
use App\Exceptions\InvalidCompanyException;
use App\Services\RedisService;
use Mockery\MockInterface;
use StockPickr\Common\Containers\UpsertCompanyContainer;
use Tests\TestCase;

class CompanyValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProviderZeroValues
     */
    public function it_should_throw_an_exception_if_required_field_is_zero(array $incomeStatement, array $balanceSheet, array $cashFlow)
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'    => 'TST',
                'name'      => 'Test Inc.',
                'sector'    => 'Tech',
                'financialStatements' => [
                    'incomeStatements'  => ['2020' => $incomeStatement],
                    'balanceSheets'     => ['2020' => $balanceSheet],
                    'cashFlows'         => ['2020' => $cashFlow]
                ]
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    /**
     * @test
     * @dataProvider dataProviderNullValues
     */
    public function it_should_throw_an_exception_if_required_field_is_null(array $incomeStatement, array $balanceSheet, array $cashFlow)
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'    => 'TST',
                'name'      => 'Test Inc.',
                'sector'    => 'Tech',
                'financialStatements' => [
                    'incomeStatements'  => ['2020' => $incomeStatement],
                    'balanceSheets'     => ['2020' => $balanceSheet],
                    'cashFlows'         => ['2020' => $cashFlow]
                ]
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    /**
     * @test
     * @dataProvider dataProviderEmptyStringValues
     */
    public function it_should_throw_an_exception_if_required_field_is_empty_string(array $incomeStatement, array $balanceSheet, array $cashFlow)
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'    => 'TST',
                'name'      => 'Test Inc.',
                'sector'    => 'Tech',
                'financialStatements' => [
                    'incomeStatements'  => ['2020' => $incomeStatement],
                    'balanceSheets'     => ['2020' => $balanceSheet],
                    'cashFlows'         => ['2020' => $cashFlow]
                ]
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    /** @test */
    public function it_should_throw_an_exception_if_ticker_is_too_long()
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'                => 'TOOLONGTICKER',
                'name'                  => 'Test Inc.',
                'sector'                => 'Tech',
                'financialStatements'   => $this->getFinancialStatements()
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TOOLONGTICKER', $ex->getMessage());
        }
    }

    /** @test */
    public function it_should_throw_an_exception_if_ticker_has_dot_in_it()
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'                => 'ELD.TO',
                'name'                  => 'Test Inc.',
                'sector'                => 'Tech',
                'financialStatements'   => $this->getFinancialStatements()
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('ELD.TO', $ex->getMessage());
        }
    }

    /** @test */
    public function it_should_throw_an_exception_if_name_is_empty()
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'                => 'TST',
                'name'                  => '',
                'sector'                => 'Tech',
                'financialStatements'   => $this->getFinancialStatements()
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    /** @test */
    public function it_should_throw_an_exception_if_sector_is_empty()
    {
        try {
            $this->mock(RedisService::class, function (MockInterface $mock) {
                $mock->shouldReceive('publishCompanyCreateFailed');
            });

            event(new UpsertCompany(UpsertCompanyContainer::from([
                'ticker'                => 'TST',
                'name'                  => 'Test inc.',
                'sector'                => '',
                'financialStatements'   => $this->getFinancialStatements()
            ]), false, 'abc-123'));
        } catch (InvalidCompanyException $ex) {
            $this->assertStringContainsString('TST', $ex->getMessage());
        }
    }

    private function getFinancialStatements(): array
    {
        return [
            'incomeStatements' => [
                '2020' => ['totalRevenue' => 100, 'grossProfit' => 100, 'ebit' => 100, 'operatingIncome' => 100, 'netIncome' => 100]
            ],
            'balanceSheets' => [
                '2020' => ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100]
            ],
            'cashFlows' => [
                '2020' => ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]
            ]
        ];
    }

    public function dataProviderZeroValues()
    {
        return [
            [['totalRevenue' =>   0,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' =>   0,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' =>   0,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' =>   0, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' =>  100, 'netIncome' =>  0], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],

            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' =>   0, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' =>   0, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' =>   0], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]]
        ];
    }

    public function dataProviderNullValues()
    {
        return [
            [['totalRevenue' => null,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => null,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => null,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => null, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => null], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],

            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => null, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => null, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => null], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => 1000]],

            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => null, 'freeCashFlow' => 1000, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => null, 'capex' => 1000]],
            [['totalRevenue' => 1000,    'grossProfit' => 1000,  'ebit' => 1000,  'operatingIncome' => 1000, 'netIncome' => 1000], ['totalAssets' => 1000, 'totalEquity' => 1000, 'cash' => 1000], ['operatingCashFlow' => 1000, 'freeCashFlow' => 1000, 'capex' => null]]
        ];
    }

    public function dataProviderEmptyStringValues()
    {
        return [
            [['totalRevenue' =>  '',    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' =>  '',  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' =>  '',  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' =>  '', 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' =>  100, 'netIncome' => ''], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],

            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' =>  '', 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' =>  '', 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' =>  ''], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' => 100]],

            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' =>  '', 'freeCashFlow' => 100, 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' =>  '', 'capex' => 100]],
            [['totalRevenue' => 100,    'grossProfit' => 100,  'ebit' => 100,  'operatingIncome' => 100, 'netIncome' => 100], ['totalAssets' => 100, 'totalEquity' => 100, 'cash' => 100], ['operatingCashFlow' => 100, 'freeCashFlow' => 100, 'capex' =>  '']]
        ];
    }
}
