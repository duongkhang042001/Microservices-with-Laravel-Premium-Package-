<?php

namespace App\Repositories;

use App\Enums\ScoreType;
use App\Models\Company;
use App\Models\CompanyScore;
use App\Models\CompanySectorScore;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;

final class CompanyRepository
{
    public function upsert(string $ticker, string $sector): Company
    {
        $company = $this->firstOrNew($ticker);
        $company->sector = $sector;

        $company->save();
        return $company;
    }

    public function firstOrNew(string $ticker): Company
    {
        return Company::firstOrNew([
            'ticker' => $ticker
        ]);
    }

    public function firstOrFail(string $ticker): Company
    {
        return Company::where('ticker', $ticker)->firstOrFail();
    }

    /**
     * @return LazyCollection<Company>
     */
    public function getAllByScores(): LazyCollection
    {
        /**
         * Percent szerint rendez, így kevesebb esély van duplikált position -re
         * Utána pedig sector score, hogy ne ABC döntsön az egyenlő cégekről
         */
        return Company::orderByDesc('total_score_percent')
            ->orderByDesc('total_sector_score_percent')
            ->cursor();
    }

    /**
     * @return LazyCollection<Company>
     */
    public function getAllWithMetric(): LazyCollection
    {
        return Company::with('metrics')
            ->with('score')
            ->with('sector_score')
            ->cursor();
    }

    /**
     * @return LazyCollection<Company>
     */
    public function getOnlyTotalScores(): LazyCollection
    {
        return Company::select([
            'ticker', 'total_scores', 'total_score_percent',
            'total_sector_scores', 'total_sector_score_percent',
            'position', 'position_percentile'
        ])->cursor();
    }

    public function updateTotalScore(
        Company $company,
        int $totalScores,
        float $totalScorePercent,
        int $totalSectorScores,
        float $totalSectorScorePercent
    ): void {
        $company->total_scores = $totalScores;
        $company->total_score_percent = $totalScorePercent;
        $company->total_sector_scores = $totalSectorScores;
        $company->total_sector_score_percent = $totalSectorScorePercent;

        $company->save();
    }

    public function updatePosition(
        Company $company,
        int $position,
        float $positionPercentile
    ): void {
        $company->position = $position;
        $company->position_percentile = $positionPercentile;

        $company->save();
    }

    public function clearPositions()
    {
        DB::table('companies')
            ->update([
                'position' => null,
                'position_percentile' => null
            ]);
    }

    public function getOrMakeScore(
        Company $company,
        string $type
    ): CompanyScore | CompanySectorScore {
        $score = match ($type) {
            ScoreType::ALL => $company->score,
            ScoreType::SECTOR => $company->sector_score,
            default => throw new InvalidArgumentException('No score found for type: ' . $type),
        };

        if ($score !== null) {
            return $score;
        }

        $data = [
            'ticker' => $company->ticker,
            'company_id' => $company->id,
        ];

        return match ($type) {
            ScoreType::ALL => new CompanyScore($data),
            ScoreType::SECTOR => new CompanySectorScore($data),
            default => throw new InvalidArgumentException('Invalid type ' . $type)
        };
    }

    /**
     * @return Collection<string>
     */
    public function getAllTickers(): Collection
    {
        return Company::select('ticker')
            ->get()->pluck('ticker');
    }

    public function delete(string $ticker): void
    {
        Company::where('ticker', $ticker)->first()?->delete();
    }
}
