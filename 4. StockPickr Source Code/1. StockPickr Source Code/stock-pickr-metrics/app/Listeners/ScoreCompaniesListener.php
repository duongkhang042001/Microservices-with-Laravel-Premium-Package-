<?php

namespace App\Listeners;

use App\Events\ScoreCompanies;
use App\Repositories\CompanyRepository;
use App\Services\RedisService;
use App\Services\ScoreService;
use Exception;
use Illuminate\Support\Facades\DB;

class ScoreCompaniesListener
{
    public function __construct(
        private ScoreService $scoreService,
        private RedisService $redis,
        private CompanyRepository $companies
    ) {}

    public function handle(ScoreCompanies $event)
    {
        // TODO: így nem lesz jó a tranzackió kezelés
        try {
            DB::beginTransaction();

            $this->scoreService->scoreCompanies();

            foreach ($this->companies->getOnlyTotalScores() as $company) {
                $this->redis->publishCompanyScored($company);
            }

            $this->redis->publishScoreSucceeded($event->scheduleId);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();

            $this->redis->publishScoreFailed(
                $event->scheduleId,
                $ex->getMessage()
            );

            throw $ex;
        }
    }
}
