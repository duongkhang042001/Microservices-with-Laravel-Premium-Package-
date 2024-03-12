<?php

namespace App\Services;

use App\Containers\MetricContainer;
use App\Enums\Scores;
use App\Enums\ScoreType;
use App\Models\Company;
use App\Models\CompanyScore;
use App\Models\CompanySectorScore;
use App\Repositories\CompanyRepository;
use App\Repositories\MetricRepository;
use App\Rules\RuleFactory;
use StockPickr\Common\Containers\Metrics\MetricsSummaryContainer;

class ScoreService
{
    public function __construct(
        private MetricMedianService $metricMedianService,
        private CompanyRepository $companies,
        private MetricRepository $metrics,
        private RuleFactory $ruleFactory
    ) {}

    public function scoreCompanies(): void
    {
        $allMedian = $this->metricMedianService->getMediansForAllCompany();
        $sectorMedians = [];

        foreach ($this->companies->getAllWithMetric() as $company) {
            $companyMedian = $this->metricMedianService->getMediansForCompany($company);
            if (!isset($sectorMedians[$company->sector])) {
                $sectorMedians[$company->sector] = $this->metricMedianService
                    ->getMediansForSector($company->sector);
            }

            $sectorMedian = $sectorMedians[$company->sector];
            [$totalScores, $totalSectorScores] = $this->updateScores(
                $company, $companyMedian, $allMedian, $sectorMedian
            );

            $this->companies->updateTotalScore(
                $company,
                $totalScores,
                $totalScores / $this->getMaxPossibleScore($companyMedian),
                $totalSectorScores,
                $totalSectorScores / $this->getMaxPossibleScore($companyMedian)
            );
        }

        $this->positionCompanies();
    }


    /**
     * @return Array<int>
     */
    private function updateScores(
        Company $company,
        MetricsSummaryContainer $companyMedian,
        MetricsSummaryContainer $allMedian,
        MetricsSummaryContainer $sectorMedian
    ): array {
        $companyScore = $this->companies->getOrMakeScore($company, ScoreType::ALL);
        $companySectorScore = $this->companies->getOrMakeScore($company, ScoreType::SECTOR);

        $totalScores = $this->updateScore(
            $companyMedian,
            $allMedian,
            $companyScore
        );
        $totalSectorScores = $this->updateScore(
            $companyMedian,
            $sectorMedian,
            $companySectorScore
        );

        return [$totalScores, $totalSectorScores];
    }

    private function updateScore(
        MetricsSummaryContainer $companyMedian,
        MetricsSummaryContainer $othersMedian,
        CompanyScore | CompanySectorScore $companyScore,
    ): int {
        $totalScores = 0;
        foreach ($this->metrics->getAll() as $metric) {
            $score = $this->getScore(
                $metric,
                $companyMedian->{$metric->slugCamel},
                $othersMedian->{$metric->slugCamel}
            );

            $companyScore->{$metric->slug} = $score;
            $totalScores += $score;
        }

        $companyScore->save();
        return $totalScores;
    }

    private function getScore(
        MetricContainer $metric,
        ?float $ownMedian,
        ?float $othersMedian
    ): ?int {
        $rule = $this->ruleFactory->create($metric);

        return match ($ownMedian === null || $othersMedian === null) {
            true => Scores::NOT_AVAILABLE,
            false => $rule->getScore($ownMedian, $othersMedian),
        };
    }

    private function getMaxPossibleScore(
        MetricsSummaryContainer $companyMedian
    ): int {
        $scorableMetricsCount = $this->metrics->getAll()
            ->reject(fn (MetricContainer $metric)
                => $companyMedian->{$metric->slugCamel} === null)
            ->count();

        return $scorableMetricsCount * Scores::A;
    }

    private function positionCompanies(): void
    {
        $this->companies->clearPositions();
        $companies = $this->companies->getAllByScores();

        foreach ($companies as $company) {
            $position = $companies->search($company) + 1;
            $this->companies->updatePosition(
                $company,
                $position,
                $position / $companies->count()
            );
        }
    }
}
