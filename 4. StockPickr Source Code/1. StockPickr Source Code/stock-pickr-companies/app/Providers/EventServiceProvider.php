<?php

namespace App\Providers;

use App\Events\Company\RemoveCompany;
use App\Events\Company\UpsertCompany;
use App\Listeners\Company\RemoveCompanyListener;
use App\Listeners\Company\UpsertCompanyListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpsertCompany::class => [
            UpsertCompanyListener::class
        ],
        RemoveCompany::class => [
            RemoveCompanyListener::class
        ]
    ];
}
