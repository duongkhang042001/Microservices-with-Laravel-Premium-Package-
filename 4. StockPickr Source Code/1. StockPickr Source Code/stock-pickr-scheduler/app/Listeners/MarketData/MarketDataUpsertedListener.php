<?php

namespace App\Listeners\MarketData;

use App\Enums\CompanyUpdateTypes;
use App\Events\MarketData\MarketDataUpserted;
use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyUpdateService;

class MarketDataUpsertedListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules,
        private CompanyUpdateService $companyUpdateService
    ) {}

    public function handle(MarketDataUpserted $event): void
    {
        match ($event->isUpdate) {
            false => $this->companySchedules->marketDataCreated($event->scheduleId, $event->data->marketData),
            true  => $this->companySchedules->marketDataUpdated($event->scheduleId, $event->data->marketData)
        };

        $this->companyUpdateService->upsert(CompanyUpdateTypes::MARKET_DATA, $event->data->ticker);
    }
}
