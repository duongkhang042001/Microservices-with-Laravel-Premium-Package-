<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Redis;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Containers\ScoreCompaniesFailedContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer;
use StockPickr\Common\Testing\Factories\CompanyUpsertedContainerFactory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private CompanyUpsertedContainerFactory $factory;

    public function setUp():void
    {
        parent::setUp();
        $this->factory = resolve(CompanyUpsertedContainerFactory::class);
    }

    protected function tearDown(): void
    {
        Redis::flushall();
        parent::tearDown();
    }

    protected function createUpsertCompanyContainer(array $data)
    {
        $data = array_merge([
            'id'        => 1,
            'ticker'    => 'TST',
            'name'      => 'Test Inc.',
            'fullName'  => 'TST - Test Inc.',
            'sector'    => 'Tech',
            'financialStatements' => [
                'incomeStatements'  => [],
                'balanceSheets'     => [],
                'cashFlows'         => [],
            ]
        ], $data);

        return UpsertCompanyContainer::from($data);
    }

    protected function createCompanyUpsertedContainer(array $data): CompanyUpsertedContainer
    {
        return $this->factory->createCompanyUpsertedContainer($data);
    }

    protected function createCompanyUpsertFailedContainer(string $ticker, string $message)
    {
        return CompanyUpsertFailedContainer::create($ticker, $message);
    }

    protected function createScoreCompaniesFailedContainer()
    {
        return ScoreCompaniesFailedContainer::create('message');
    }

    protected function createUpsertMarketDataContainer(array $data)
    {
        $data = array_merge([
            'ticker'        => 'TST',
            'scheduleId'    => 'abc-123',
            'marketData' => [
                'shareData'     => [
                    'price' => 100
                ],
                'analyst'       => []
            ]
        ], $data);

        return UpsertMarketDataContainer::from($data);
    }
}
