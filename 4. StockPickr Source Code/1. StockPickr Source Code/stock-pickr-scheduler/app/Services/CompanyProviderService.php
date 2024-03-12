<?php

namespace App\Services;

use App\Repositories\CompanyUpdateRepository;
use App\Repositories\DeniedCompanyRepository;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use StockPickr\Common\Containers\AnalystContainer;
use StockPickr\Common\Containers\MarketData\MarketDataContainer;
use StockPickr\Common\Containers\ShareDataContainer;
use StockPickr\Common\Containers\UpsertCompanyContainer as CompanyContainer;

class CompanyProviderService
{
    public function __construct(
        private string $url,
        private CompanyUpdateRepository $companyUpdates,
        private DeniedCompanyRepository $deniedCompanies
    ) {}

    /**
     * @return array{alternativeTicker: string, company: CompanyContainer}
     */
    public function getNextCompanyForCreate(): array
    {
        $availableTickers = $this->getAvailableTickers();
        $createdTickers = $this->companyUpdates->getAllTickers();
        $deniedTickers = $this->deniedCompanies->getAllTickers();

        $ticker = collect($availableTickers)
            ->reject(fn (string $ticker) => $createdTickers->contains($ticker))
            ->reject(fn (string $ticker) => $deniedTickers->contains($ticker))
            ->first();

        if (! $ticker) {
            throw new Exception('No tickers to install');
        }

        /**
         * Vannak olyan cégek, ahol az available-tickers NOK tickert ad vissza,
         * de a company profile -ban már NOKIA.HE szerepel. Mindkettőre szükség
         * van, mert problémát okoz denied_companies szempontjából. Ha itt csak
         * a company van vissza adva, akkor máshol csak a NOKIA.HE tickert
         * lehet kitiltani, de következő futáskor az available-ticker végpont
         * NOK tickert fig visszaadni, ami nincs kitiltva, ezért újra
         * megpróbálja létrehozni.
         */
        return [
            'alternativeTicker' => $ticker,
            'company' => $this->getCompany($ticker)
        ];
    }

    public function getNextCompanyForUpdate(): CompanyContainer
    {
        $ticker = $this->companyUpdates->getOldestUpdatedTicker('financials_updated_at');
        return $this->getCompany($ticker);
    }

    public function getNextTickerForShareDataUpdate(): string
    {
        return $this->companyUpdates->getOldestUpdatedTicker('market_data_updated_at');
    }

    public function getCompany(string $ticker): CompanyContainer
    {
        try {
            $company = Http::get($this->url . "companies/$ticker")->throw()->json()['data'];
            return CompanyContainer::from($company);
        } catch (Exception $ex) {
            $this->deniedCompanies->create($ticker, $ex->getMessage());
            throw $ex;
        }
    }

    public function getMarketData(
        string $ticker,
        string $alternativeTicker
    ): MarketDataContainer {
        try {
            return MarketDataContainer::create($this->getShareData($ticker), $this->getAnalyst($ticker));
        } catch (Exception $ex) {
            $this->deniedCompanies->create($ticker, $ex->getMessage());
            $this->deniedCompanies->create($alternativeTicker, $ex->getMessage());
            throw $ex;
        }
    }

    public function getShareData(string $ticker): ShareDataContainer
    {
        $data = Http::get($this->url . "companies/$ticker/share-data")->throw()->json()['data'];
        return ShareDataContainer::from($data);
    }

    public function getAnalyst(string $ticker): AnalystContainer
    {
        try {
            $data = Http::get($this->url . "companies/$ticker/analyst")->throw()->json()['data'];
            return AnalystContainer::from($data);
        } catch (Exception $ex) {
            $this->deniedCompanies->create($ticker, $ex->getMessage());
            throw $ex;
        }
    }

    public function getAvailableTickers(): Collection
    {
        $tickers = Http::get($this->url . 'available-tickers')->throw()->json()['data'];
        return collect($tickers);
    }
}
