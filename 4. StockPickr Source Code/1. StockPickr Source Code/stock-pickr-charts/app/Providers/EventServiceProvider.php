<?php

namespace App\Providers;

use App\Events\CompanyUpserted;
use App\Events\CreateCharts;
use App\Events\MetricsUpserted;
use App\Events\RemoveCompany;
use App\Listeners\CompanyUpsertedListener;
use App\Listeners\CreateChartsListener;
use App\Listeners\MetricsUpsertedListener;
use App\Listeners\RemoveCompanyListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CompanyUpserted::class => [
            CompanyUpsertedListener::class,
        ],

        MetricsUpserted::class => [
            MetricsUpsertedListener::class
        ],

        CreateCharts::class => [
            CreateChartsListener::class
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
