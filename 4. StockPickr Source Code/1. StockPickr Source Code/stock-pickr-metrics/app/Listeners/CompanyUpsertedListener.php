<?php

namespace App\Listeners;

use App\Events\CompanyUpserted;
use App\Repositories\CompanyRepository;
use App\Services\MetricMedianService;
use App\Services\MetricContainerService;
use App\Services\MetricService;
use App\Services\RedisService;
use DB;
use Exception;

class CompanyUpsertedListener
{
    public function __construct(
        private MetricService $metricService,
        private MetricMedianService $metricMedianService,
        private MetricContainerService $metricsContainerService,
        private RedisService $redis,
        private CompanyRepository $companies
    ) {}

    public function handle(CompanyUpserted $event)
    {
        try {
            DB::beginTransaction();

            $company = $this->companies->upsert(
                $event->company->ticker,
                $event->company->sector->name
            );

            $metricsContainer = $this->metricService->upsert($company, $event->company);
            $this->metricMedianService->upsert($company);

            match ($event->isUpdate) {
                false => $this->redis->publishMetricsCreatedEvent(
                    $event->company,
                    $metricsContainer
                ),
                true => $this->redis->publishMetricsUpdatedEvent(
                    $event->company,
                    $metricsContainer
                ),
            };

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();

            match ($event->isUpdate) {
                false => $this->redis->publishMetricsCreateFailedEvent($event->company->ticker, $ex->getMessage()),
                true => $this->redis->publishMetricsUpdateFailedEvent($event->company->ticker, $ex->getMessage()),
            };

            throw $ex;
        }
    }
}
