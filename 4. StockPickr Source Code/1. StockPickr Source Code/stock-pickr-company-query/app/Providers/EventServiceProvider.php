<?php

namespace App\Providers;

use App\Events\CompanyScored;
use App\Events\CompanyUpserted;
use App\Events\RemoveCompany;
use App\Events\ScoreSucceeded;
use App\Listeners\CompanyScoredListener;
use App\Listeners\CompanyUpsertedListener;
use App\Listeners\RemoveCompanyListener;
use App\Listeners\ScoreSucceededListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CompanyUpserted::class => [
            CompanyUpsertedListener::class
        ],
        CompanyScored::class => [
            CompanyScoredListener::class
        ],
        ScoreSucceeded::class => [
            ScoreSucceededListener::class
        ],
        RemoveCompany::class => [
            RemoveCompanyListener::class
        ]
    ];

    public function boot()
    {
        //
    }
}
