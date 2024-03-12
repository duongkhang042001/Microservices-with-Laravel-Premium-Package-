<?php

namespace App\Listeners;

use App\Events\RemoveCompany;
use App\Repositories\CompanyRepository;
use App\Services\CompanyService;
use DB;

class RemoveCompanyListener
{
    public function __construct(
        private CompanyRepository $companies
    ) {}

    public function handle(RemoveCompany $event): void
    {
        DB::transaction(function () use ($event) {
            $this->companies->delete($event->ticker);
        });
    }
}
