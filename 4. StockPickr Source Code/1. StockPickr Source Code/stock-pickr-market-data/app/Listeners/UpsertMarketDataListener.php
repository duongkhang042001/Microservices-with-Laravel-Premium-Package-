<?php

namespace App\Listeners;

use App\Events\UpsertMarketData;
use App\Services\AnalystService;
use App\Services\ShareDataService;
use App\Services\RedisService;
use Exception;

class UpsertMarketDataListener
{
    public function __construct(
        private ShareDataService $shareDataService,
        private AnalystService $analystService,
        private RedisService $redis
    ) {}

    public function handle(UpsertMarketData $event): void
    {
        try {
            $this->shareDataService->upsert($event->data->ticker, $event->data->marketData->getShareData());

            try {
                // Analyst nem kritikus adat, és share data + analyst nem számít egy atomic műveletnek
                $this->analystService->upsert($event->data->ticker, $event->data->marketData->getAnalyst());
            } catch (Exception $ex) {

                if (!$event->isUpdate) {
                    throw $ex;
                }

                logger($ex->getMessage());
            }

            switch ($event->isUpdate) {
                case true:
                    $this->redis->publishMarketDataUpdated($event->data->ticker, $event->data->marketData, $event->scheduleId);
                    break;
                case false:
                    $this->redis->publishMarketDataCreated($event->data->ticker, $event->data->marketData, $event->scheduleId);
                    break;
            }
        } catch (Exception $ex) {
            // phpstan nem értette a match -et
            switch ($event->isUpdate) {
                case true:
                    $this->redis->publishMarketDataUpdateFailed($event->data->ticker, $ex->getMessage(), $event->scheduleId);
                    break;
                case false:
                    $this->redis->publishMarketDataCreateFailed($event->data->ticker, $ex->getMessage(), $event->scheduleId);
                    break;
            }

            throw $ex;
        }
    }
}
