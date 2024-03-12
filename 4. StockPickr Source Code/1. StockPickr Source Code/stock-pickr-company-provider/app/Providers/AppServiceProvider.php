<?php

namespace App\Providers;

use App\Services\Finnhub;
use App\Services\MarketDataProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $priorityTickers = config('datasource.use-priority-tickers')
            ? config('priority-tickers')
            : [];

        if (App::environment() === 'testing') {
            $priorityTickers = [];
        }

        $this->app->bind(MarketDataProvider::class, Finnhub::class);

        $this->app->when(Finnhub::class)
            ->needs('$priorityTickers')
            ->give($priorityTickers);
    }

    public function boot()
    {
    }
}
