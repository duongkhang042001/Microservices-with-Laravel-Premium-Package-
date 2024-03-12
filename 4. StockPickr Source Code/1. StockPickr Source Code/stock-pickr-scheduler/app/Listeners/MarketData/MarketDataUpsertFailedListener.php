<?php

namespace App\Listeners\MarketData;

use App\Events\MarketData\MarketDataUpsertFailed;
use App\Repositories\CompanyScheduleRepository;

class MarketDataUpsertFailedListener
{
    public function __construct(private CompanyScheduleRepository $companySchedules)
    {
    }

    public function handle(MarketDataUpsertFailed $event): void
    {
        match ($event->isUpdate) {
            false   => $this->companySchedules->marketDataCreateFailed($event->scheduleId, $event->data->message),
            true    => $this->companySchedules->marketDataUpdateFailed($event->scheduleId, $event->data->message)
        };
    }
}
