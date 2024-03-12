<?php

namespace App\Providers;

use App\Events\DeleteCompany;
use App\Events\UpsertMarketData;
use App\Listeners\DeleteCompanyListener;
use App\Listeners\UpsertMarketDataListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpsertMarketData::class => [
            UpsertMarketDataListener::class
        ],
        DeleteCompany::class => [
            DeleteCompanyListener::class
        ]
    ];
}
