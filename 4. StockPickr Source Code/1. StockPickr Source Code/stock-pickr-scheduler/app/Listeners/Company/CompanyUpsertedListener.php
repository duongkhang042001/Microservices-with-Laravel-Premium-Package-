<?php

namespace App\Listeners\Company;

use App\Events\Company\CompanyUpserted;
use App\Repositories\CompanyScheduleRepository;
use App\Repositories\CompanyUpdateRepository;

class CompanyUpsertedListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules,
        private CompanyUpdateRepository $companyUpdates
    ) {}

    public function handle(CompanyUpserted $event): void
    {
        match ($event->isUpdate) {
            true    => $this->companySchedules->companyUpdateSucceeded($event->scheduleId, $event->company),
            false   => $this->companySchedules->companyCreateSucceeded($event->scheduleId, $event->company)
        };

        $this->companyUpdates->upsertForFinancials($event->company->ticker, now());
    }
}
