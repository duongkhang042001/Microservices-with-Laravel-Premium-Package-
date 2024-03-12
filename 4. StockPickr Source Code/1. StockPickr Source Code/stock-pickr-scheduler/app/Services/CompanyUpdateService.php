<?php

namespace App\Services;

use App\Enums\CompanyUpdateTypes;
use App\Models\CompanyUpdate;
use App\Repositories\CompanyUpdateRepository;
use App\Repositories\DeniedCompanyRepository;
use Exception;
use InvalidArgumentException;

class CompanyUpdateService
{
    public function __construct(
        private CompanyUpdateRepository $companyUpdates,
        private DeniedCompanyRepository $deniedCompanies
    ) {}

    public function upsert(string $type, string $ticker): CompanyUpdate
    {
        /**
         * Ilyen akkor fordulhat elő, ha pl egy create-company filed állapotba került
         * de a create-market-data utána fejeződik be. Ebben az esetben a create-company
         * beteszi denied_companies közé, így a market-data már nem próbál
         * company_schedules -t frissíteni. Enélkül inkonzisztens adatok lesznek
         * a táblában
         */
        $deniedTickers = $this->deniedCompanies->getAllTickers();
        if ($deniedTickers->contains($ticker)) {
            throw new Exception('Company ' . $ticker . ' is denied');
        }

        return match ($type) {
            CompanyUpdateTypes::MARKET_DATA => $this->companyUpdates->upsertForMarketData($ticker, now()),
            CompanyUpdateTypes::FINANCIALS => $this->companyUpdates->upsertForFinancials($ticker, now()),
            default => throw new InvalidArgumentException('No company update type for ' . $type)
        };
    }
}
