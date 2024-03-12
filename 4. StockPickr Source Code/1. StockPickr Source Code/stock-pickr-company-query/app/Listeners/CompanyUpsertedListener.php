<?php

namespace App\Listeners;

use App\Events\CompanyUpserted;
use App\Services\CompanyService;

class CompanyUpsertedListener
{
    public function __construct(private CompanyService $companyService)
    {
    }

    public function handle(CompanyUpserted $event)
    {
        $this->companyService->upsert($event->company);
    }
}
