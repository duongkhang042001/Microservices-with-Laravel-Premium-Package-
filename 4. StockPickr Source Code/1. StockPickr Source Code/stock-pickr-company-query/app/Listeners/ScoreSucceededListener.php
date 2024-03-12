<?php

namespace App\Listeners;

use App\Repositories\CompanyRepository;
use App\Repositories\ScoreQueueRepository;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ScoreSucceededListener
{
    public function __construct(
        private ScoreQueueRepository $scoreQueue,
        private CompanyService $companyService,
        private CompanyRepository $companies
    ) {}

    public function handle()
    {
        DB::transaction(function () {
            $this->companies->clearPositions();
            $this->companyService->processScoreQueue();
            Cache::flush();
        });
    }
}
