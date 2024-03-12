<?php

namespace App\Listeners\Company;

use App\Events\Company\CompanyUpsertFailed;
use App\Repositories\CompanyScheduleRepository;
use App\Repositories\CompanyUpdateRepository;
use App\Repositories\DeniedCompanyRepository;
use App\Services\RedisService;

class CompanyUpsertFailedListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules,
        private DeniedCompanyRepository $deniedCompanies,
        private CompanyUpdateRepository $companyUpdates,
        private RedisService $redis
    ) {}

    public function handle(CompanyUpsertFailed $event): void
    {
        if ($event->isUpdate) {
            $this->companySchedules->companyUpdateFailed($event->scheduleId, $event->data->message);
        } else {
            $this->companySchedules->companyCreateFailed($event->scheduleId, $event->data->message);
            $this->deniedCompanies->create($event->data->ticker, $event->data->message);
            $this->companyUpdates->remove($event->data->ticker);
            $this->redis->publishRemoveCompany($event->data->ticker);
        }
    }
}
