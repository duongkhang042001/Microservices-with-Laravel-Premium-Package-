<?php

namespace App\Services;

use App\Containers\HttpConfig;
use Illuminate\Support\Arr;
use App\Services\Mapper\FinnhubMapper;
use App\Services\Mapper\MarketDataProviderMapper;
use Illuminate\Support\Facades\App;
use StockPickr\Common\Containers\AnalystContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer as CompanyContainer;
use StockPickr\Common\Containers\ShareDataContainer;

class Finnhub extends MarketDataProvider
{
    public function getCompany(string $ticker): CompanyContainer
    {
        $company = $this->getCompanyData($ticker);

        $incomeStatements = $this->getIncomeStatements($ticker);
        $balanceSheets = $this->getBalanceSheets($ticker);
        $cashFlows = $this->getCashFlows($ticker);

        $peers = $this->getPeers($ticker);

        return CompanyContainer::from(
            array_merge($company, [
                'financialStatements' => [
                    'incomeStatements'  => $incomeStatements,
                    'balanceSheets'     => $balanceSheets,
                    'cashFlows'         => $cashFlows
                ],
                'peers'                 => $peers
            ])
        );
    }

    public function getShareData(string $ticker): ShareDataContainer
    {
        return ShareDataContainer::from(
            $this->getShareDataAsArray($ticker)
        );
    }

    public function getAnalyst(string $ticker): AnalystContainer
    {
        return AnalystContainer::from(
            $this->getAnalystAsArray($ticker)
        );
    }

    public function getBalanceSheets(string $ticker): array
    {
        $response = $this->httpClient->get('/stock/financials', ['symbol' => $ticker, 'statement' => 'bs', 'freq' => 'annual']);
        $financials = $this->takeLast($response['financials'], 5);

        return $this->mapper->mapStatement($financials, 'balanceSheet');
    }

    public function getIncomeStatements(string $ticker): array
    {
        $response = $this->httpClient->get('/stock/financials', ['symbol' => $ticker, 'statement' => 'ic', 'freq' => 'annual']);
        $financials = $this->takeLast($response['financials'], 5);

        return $this->mapper->mapStatement($financials, 'incomeStatement');
    }

    public function getCashFlows(string $ticker): array
    {
        $response = $this->httpClient->get('/stock/financials', ['symbol' => $ticker, 'statement' => 'cf', 'freq' => 'annual']);
        $financials = $this->takeLast($response['financials'], 5);

        return $this->mapper->mapStatement($financials, 'cashFlow');
    }

    public function getAvailableTickers(): array
    {
        if (! empty($this->priorityTickers)) {
            return collect($this->priorityTickers)->unique()->all();
        }

        $stocks = $this->httpClient->get('/stock/symbol', ['exchange' => 'US']);
        return $this->mapper->mapStocks($stocks);
    }

    protected function getCompanyData(string $ticker): array
    {
        return $this->mapper->mapCompanyData($this->getCompanyDataRaw($ticker));
    }

    protected function getCompanyDataRaw(string $ticker): array
    {
        $companyData = $this->httpClient->get('/stock/profile', ['symbol' => $ticker]);
        $ceo = $this->getCeo($ticker);
        $companyData['ceo'] = $ceo;

        return $companyData;
    }

    protected function getShareDataAsArray(string $ticker): array
    {
        $companyData = $this->getCompanyDataRaw($ticker);
        $price = $this->getCurrentPrice($ticker);
        $beta = $this->getBeta($ticker);

        return $this->mapper->mapShareData($companyData, $price, $beta);
    }

    protected function getAnalystAsArray(string $ticker): array
    {
        return $this->mapper->mapAnalystData($this->getPriceTarget($ticker), $this->getMostRecentRecommendation($ticker));
    }

    protected function getPriceTarget(string $ticker): array
    {
        return $this->httpClient->get('/stock/price-target', ['symbol' => $ticker]);
    }

    protected function getMostRecentRecommendation(string $ticker): array
    {
        $recommendations = $this->httpClient->get('/stock/recommendation', ['symbol' => $ticker]);
        $mostRecent = collect($recommendations)
            ->sortByDesc('period')
            ->first();

        return $mostRecent === null
            ? []
            : $mostRecent;
    }

    protected function getCeo(string $ticker): ?string
    {
        $data = $this->httpClient->get('/stock/executive', ['symbol' => $ticker]);
        return $this->mapper->mapCeo($data);
    }

    public function getCurrentPrice(string $ticker): ?float
    {
        $priceData = $this->httpClient->get('/quote', ['symbol' => $ticker]);
        return Arr::get($priceData, 'c');
    }

    public function getPeers(string $ticker): array
    {
        $peers = $this->httpClient->get('/stock/peers', ['symbol' => $ticker]);
        return collect($peers)
            ->reject(fn ($peer) => $peer === $ticker)
            ->values()
            ->all();
    }

    protected function getBeta(string $ticker): ?float
    {
        $metrics = $this->httpClient->get('/stock/metric', [
            'symbol'    => $ticker,
            'metric'    => 'price'
        ]);

        return Arr::get($metrics, 'metric.beta');
    }

    protected function getHttpConfig(): HttpConfig
    {
        return new HttpConfig(
            config('finnhub.base_url'),
            config('finnhub.api_token')
        );
    }

    protected function createMapper(): MarketDataProviderMapper
    {
        return resolve(FinnhubMapper::class);
    }

    protected function takeLast(array $items, int $n)
    {
        return collect($items)
            ->sortByDesc('year')
            ->take($n)
            ->values()->all();
    }
}
