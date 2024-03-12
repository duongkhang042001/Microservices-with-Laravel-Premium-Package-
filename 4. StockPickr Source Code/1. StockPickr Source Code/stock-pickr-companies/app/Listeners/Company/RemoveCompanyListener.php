<?php

namespace App\Listeners\Company;

use App\Events\Company\RemoveCompany;
use App\Repositories\Company\CompanyRepository;
use Illuminate\Support\Facades\DB;

class RemoveCompanyListener
{
    public function __construct(
        private CompanyRepository $companies
    ) {}

    public function handle(RemoveCompany $event)
    {
        DB::transaction(function () use ($event) {
            $this->companies->delete($event->ticker);
        });
    }
}
