<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;

class CompanyRepository
{
    public const NUMBER_OF_SEARCH_RESULTS = 48;

    public function upsert(CompanyUpsertedContainer $companyContainer): Company
    {
        $company = Company::firstOrNew([
            'ticker' => $companyContainer->ticker
        ]);

        $company->sector = $companyContainer->sector->name;
        $company->name = $companyContainer->name;
        $company->save();

        return $company;
    }

    public function updateScores(
        Company $company,
        CompanyScoredContainer $data
    ): Company {
        $company->total_scores = $data->totalScores;
        $company->total_score_percent = $data->totalScorePercent;
        $company->total_sector_scores = $data->totalSectorScores;
        $company->total_sector_score_percent = $data->totalSectorScorePercent;
        $company->position = $data->position;
        $company->position_percentile = $data->positionPercentile;

        $company->save();
        return $company;
    }

    public function getByTicker(string $ticker): Company
    {
        return Company::where('ticker', $ticker)->firstOrFail();
    }

    /**
     * @return Collection<Company>
     */
    public function search(string $searchTerm): Collection
    {
        return Company::select(['ticker', 'name', 'total_scores', 'total_score_percent', 'position'])
            ->where(function ($query) use ($searchTerm) {
                $query->where('ticker', 'LIKE', $searchTerm . '%')
                    ->orWhere('name', 'LIKE', $searchTerm . '%');
            })
            ->whereNotNull('position')
            ->limit(self::NUMBER_OF_SEARCH_RESULTS)
            ->get();
    }

    public function count(): int
    {
        return Company::select('id')
            ->whereNotNull('position')
            ->count();
    }

    public function delete(string $ticker): void
    {
        Company::where('ticker', $ticker)->delete();
    }

    public function clearPositions(): void
    {
        DB::table('companies')
            ->update([
                'position' => null
            ]);
    }
}
