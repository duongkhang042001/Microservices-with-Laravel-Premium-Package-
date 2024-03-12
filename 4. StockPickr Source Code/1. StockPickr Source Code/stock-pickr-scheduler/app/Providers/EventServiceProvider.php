<?php

namespace App\Providers;

use App\Events\Charts\ChartsCreateFailed;
use App\Events\Company\CompanyUpserted;
use App\Events\Company\CompanyUpsertFailed;
use App\Events\MarketData\MarketDataUpserted;
use App\Events\MarketData\MarketDataUpsertFailed;
use App\Events\Metrics\MetricsCreateFailed;
use App\Events\Score\ScoreCompaniesFailed;
use App\Events\Score\ScoreCompaniesSucceeded;
use App\Listeners\Charts\ChartsCreateFailedListener;
use App\Listeners\Company\CompanyUpsertedListener;
use App\Listeners\Company\CompanyUpsertFailedListener;
use App\Listeners\MarketData\MarketDataUpsertedListener;
use App\Listeners\MarketData\MarketDataUpsertFailedListener;
use App\Listeners\Metrics\MetricsCreateFailedListener;
use App\Listeners\Score\ScoreCompaniesFailedListener;
use App\Listeners\Score\ScoreCompaniesSucceededListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CompanyUpserted::class => [
            CompanyUpsertedListener::class
        ],
        CompanyUpsertFailed::class => [
            CompanyUpsertFailedListener::class
        ],

        MarketDataUpserted::class => [
            MarketDataUpsertedListener::class
        ],
        MarketDataUpsertFailed::class => [
            MarketDataUpsertFailedListener::class
        ],

        ScoreCompaniesSucceeded::class => [
            ScoreCompaniesSucceededListener::class
        ],
        ScoreCompaniesFailed::class => [
            ScoreCompaniesFailedListener::class
        ],
        MetricsCreateFailed::class => [
            MetricsCreateFailedListener::class
        ],
        ChartsCreateFailed::class => [
            ChartsCreateFailedListener::class
        ]
    ];

    public function boot()
    {
    }
}
