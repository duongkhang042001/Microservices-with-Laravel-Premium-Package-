<?php

namespace App\Listeners\Charts;

use App\Events\Charts\ChartsCreateFailed;
use App\Repositories\CompanyScheduleRepository;
use App\Repositories\CompanyUpdateRepository;
use App\Repositories\DeniedCompanyRepository;
use App\Services\RedisService;
use DB;

class ChartsCreateFailedListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules,
        private DeniedCompanyRepository $deniedCompanies,
        private CompanyUpdateRepository $companyUpdates,
        private RedisService $redis
    ) {}

    public function handle(ChartsCreateFailed $event): void
    {
        DB::transaction(function () use ($event) {
            $this->companySchedules->chartsCreateFailed($event->data->ticker, $event->data->message);
            $this->companyUpdates->remove($event->data->ticker);
            $this->deniedCompanies->create($event->data->ticker, $event->data->message);

            $this->redis->publishRemoveCompany($event->data->ticker);
        });
    }
}
