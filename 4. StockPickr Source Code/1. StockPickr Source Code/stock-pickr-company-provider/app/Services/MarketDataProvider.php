<?php

namespace App\Services;

use App\Containers\HttpConfig;
use App\Services\Http\HttpClient;
use App\Services\Mapper\MarketDataProviderMapper;
use StockPickr\Common\Containers\AnalystContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer as CompanyContainer;
use StockPickr\Common\Containers\ShareDataContainer;

abstract class MarketDataProvider
{
    protected HttpClient $httpClient;
    protected MarketDataProviderMapper $mapper;
    protected array $priorityTickers;

    public function __construct(
        HttpClient $httpClient,
        array $priorityTickers
    ) {
        $config = $this->getHttpConfig();
        $httpClient->setBaseUrl($config->baseUrl);
        $httpClient->setApiToken($config->apiToken);

        $this->httpClient = $httpClient;
        $this->mapper = $this->createMapper();
        $this->priorityTickers = $priorityTickers;
    }

    abstract public function getCompany(string $ticker): CompanyContainer;

    abstract public function getAnalyst(string $ticker): AnalystContainer;
    abstract public function getShareData(string $ticker): ShareDataContainer;
    abstract public function getCurrentPrice(string $ticker): ?float;

    /**
     * <code>
     * [
     *   '2020' => [
     *      'totalAssets' => 100
     *   ]
     * ];
     * </code>
     *
     * @return array[]
     */
    abstract public function getBalanceSheets(string $ticker): array;
    /**
     * <code>
     * [
     *   '2020' => [
     *      'totalRevenue' => 100
     *   ]
     * ];
     * </code>
     *
     * @return array[]
     */
    abstract public function getIncomeStatements(string $ticker): array;
    /**
     * <code>
     * [
     *   '2020' => [
     *      'operatingCashFlow' => 100
     *   ]
     * ];
     * </code>
     *
     * @return array[]
     */
    abstract public function getCashFlows(string $ticker): array;
    /**
     * @return string[]
     */
    abstract public function getPeers(string $ticker): array;
    /**
     * @return string[]
     */
    abstract public function getAvailableTickers(): array;

    abstract protected function getHttpConfig(): HttpConfig;
    abstract protected function createMapper(): MarketDataProviderMapper;
}
