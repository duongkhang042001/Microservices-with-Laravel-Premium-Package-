<?php

namespace App\Providers;

use App\Events\CompanyUpserted;
use App\Events\RemoveCompany;
use App\Events\ScoreCompanies;
use App\Listeners\CompanyUpsertedListener;
use App\Listeners\RemoveCompanyListener;
use App\Listeners\ScoreCompaniesListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CompanyUpserted::class => [
            CompanyUpsertedListener::class
        ],
        ScoreCompanies::class => [
            ScoreCompaniesListener::class
        ],
        RemoveCompany::class => [
            RemoveCompanyListener::class
        ]
    ];
}
