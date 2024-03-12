<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Repositories\ScoreQueueRepository;
use Illuminate\Support\Collection;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companies,
        private ScoreQueueRepository $scoreQueue
    ) {}

    public function upsert(CompanyUpsertedContainer $companyContainer): Company
    {
        return $this->companies->upsert($companyContainer);
    }

    public function processScoreQueue(): void
    {
        while (! $this->scoreQueue->isEmpty()) {
            $json = $this->scoreQueue->dequeue();
            $container = CompanyScoredContainer::fromJsonArray(json_decode($json, true));
            $this->updateScores($container);
        }
    }

    public function updateScores(CompanyScoredContainer $data): Company
    {
        $company = $this->companies->getByTicker($data->ticker);
        return $this->companies->updateScores($company, $data);
    }

    /**
     * @return Collection<Company>
     */
    public function search(string $searchTerm): Collection
    {
        return $this->companies->search($searchTerm);
    }

    public function count(): int
    {
        return $this->companies->count();
    }
}
