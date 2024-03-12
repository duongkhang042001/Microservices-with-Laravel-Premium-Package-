<?php

namespace App\Listeners\Company;

use App\Events\Company\UpsertCompany;
use App\Services\Company\CompanyService;
use App\Services\RedisService;
use Exception;
use Illuminate\Support\Facades\DB;

class UpsertCompanyListener
{
    public function __construct(
        private CompanyService $companyService,
        private RedisService $redis,
    ) {}

    public function handle(UpsertCompany $event)
    {
        DB::transaction(function () use ($event) {
            try {
                $company = $this->companyService->upsert($event->company);

                match ($event->isUpdate) {
                    true    => $this->redis->publishCompanyUpdated($this->companyService->convertToContainer($company), $event->scheduleId),
                    false   => $this->redis->publishCompanyCreated($this->companyService->convertToContainer($company), $event->scheduleId)
                };

            } catch (Exception $ex) {
                match ($event->isUpdate) {
                    true    => $this->redis->publishCompanyUpdateFailed($event->company->ticker, $ex->getMessage(), $event->scheduleId),
                    false   => $this->redis->publishCompanyCreateFailed($event->company->ticker, $ex->getMessage(), $event->scheduleId)
                };

                throw $ex;
            }
        });
    }
}
