<?php

namespace App\Listeners;

use App\Events\RemoveCompany;
use App\Repositories\CompanyRepository;

class RemoveCompanyListener
{
    public function __construct(private CompanyRepository $companies)
    {
    }

    public function handle(RemoveCompany $event)
    {
        $this->companies->delete($event->ticker);
    }
}
