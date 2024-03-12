<?php

namespace App\Repositories;

use App\Models\CompanyUpdate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CompanyUpdateRepository
{
    public function upsertForFinancials(string $ticker, Carbon $financialsUpdatedAt): CompanyUpdate {
        $companyUpdate = CompanyUpdate::firstOrCreate([
            'ticker' => $ticker
        ]);

        $companyUpdate->financials_updated_at = $financialsUpdatedAt;
        $companyUpdate->save();

        return $companyUpdate;
    }

    public function upsertForMarketData(string $ticker, Carbon $marketDataUpdatedAt): CompanyUpdate
    {
        $companyUpdate = CompanyUpdate::firstOrCreate([
            'ticker'        => $ticker
        ]);

        $companyUpdate->market_data_updated_at = $marketDataUpdatedAt;
        $companyUpdate->save();

        return $companyUpdate;
    }

    public function getAllTickers(): Collection
    {
        return CompanyUpdate::select(['ticker'])
            ->groupBy('ticker')
            ->get()
            ->pluck('ticker');
    }

    public function getOldestUpdatedTicker(string $column): string
    {
        return CompanyUpdate::select(['ticker'])
            ->whereNotNull($column)     // Azért kell, mert különben olya céget próbál update -elni, ami nem jött létre, csak a market-data
            ->orderBy($column)
            ->limit(1)
            ->firstOrFail()
            ->ticker;
    }

    public function remove(string $ticker): void
    {
        CompanyUpdate::where('ticker', $ticker)
            ->delete();
    }
}
