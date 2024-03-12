<?php

namespace App\Listeners;

use App\Events\CompanyUpserted;
use App\Services\CompanyService;
use App\Services\RedisService;
use Exception;

class CompanyUpsertedListener
{
    public function __construct(
        private CompanyService $companyService,
        private RedisService $redis
    ) {}

    public function handle(CompanyUpserted $event): void
    {
        try {
            $this->companyService->upsert($event->company);
        } catch (Exception $ex) {
            $this->redis->publishChartsCreateFailed($event->company->ticker, $ex->getMessage());
        }
    }
}
