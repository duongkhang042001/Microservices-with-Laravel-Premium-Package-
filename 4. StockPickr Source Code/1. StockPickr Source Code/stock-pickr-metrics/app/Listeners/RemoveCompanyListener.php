<?php

namespace App\Listeners;

use App\Events\RemoveCompany;
use App\Repositories\CompanyRepository;
use DB;

class RemoveCompanyListener
{
    public function __construct(private CompanyRepository $companies)
    {
    }

    public function handle(RemoveCompany $event)
    {
        DB::transaction(function () use ($event) {
            $this->companies->delete($event->ticker);
        });
    }
}
