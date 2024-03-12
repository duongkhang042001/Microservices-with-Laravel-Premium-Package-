<?php

namespace App\Listeners;

use App\Events\CreateCharts;
use App\Services\ChartService;
use App\Services\RedisService;
use Exception;

class CreateChartsListener
{
    public function __construct(
        private ChartService $chartService,
        private RedisService $redis
    ) {}

    public function handle(CreateCharts $event): void
    {
        try {
            $this->chartService->upsert($event->company);
        } catch (Exception $ex) {
            $this->redis->publishChartsCreateFailed($event->company->ticker, $ex->getMessage());
        }
    }
}
