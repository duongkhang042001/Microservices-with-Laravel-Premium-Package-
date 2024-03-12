<?php

namespace App\Repositories;

use App\Models\DeniedCompany;
use Illuminate\Support\Collection;
use Str;

class DeniedCompanyRepository
{
    public function create(string $ticker, string $reason): DeniedCompany
    {
        /**
         * Van olyan eset Finnhub -on, hogy lekérek egy céget AZN tickerrel
         * de a visszakapott adatokban AZN.L szerepel. Ebben az esetben csak
         * az AZN.L -t mentette el denied_companies táblába, tehát továbbra
         * is próbála létrehozni az AZN céget.
         */
        if (Str::contains($ticker, '.')) {
            DeniedCompany::create([
                'ticker' => explode('.', $ticker)[0],
                'reason' => $reason
            ]);
        }

        return DeniedCompany::create(compact('ticker', 'reason'));
    }

    public function getAllTickers(): Collection
    {
        return DeniedCompany::select(['ticker'])
            ->get()
            ->pluck('ticker');
    }
}
