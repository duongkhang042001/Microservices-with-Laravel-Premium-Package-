<?php

namespace App\Listeners;

use App\Events\MetricsUpserted;
use App\Services\CompanyService;
use App\Services\RedisService;
use Exception;

class MetricsUpsertedListener
{
    public function __construct(
        private CompanyService $companyService,
        private RedisService $redis
    ) {}

    public function handle(MetricsUpserted $event): void
    {
        try {
            $this->companyService->updateMetrics($event->data);
        } catch (Exception $ex) {
            $this->redis->publishChartsCreateFailed($event->data->ticker, $ex->getMessage());
        }
    }
}
