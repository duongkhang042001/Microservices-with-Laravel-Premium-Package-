<?php

namespace App\Providers;

use App\Services\HttpClient;
use App\Services\ProductService;
use App\Services\RatingService;
use App\Services\WarehouseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(ProductService::class)
            ->needs(HttpClient::class)
            ->give(fn () => new HttpClient(config('services.products.url')));

        $this->app->when(WarehouseService::class)
            ->needs(HttpClient::class)
            ->give(fn () => new HttpClient(config('services.warehouse.url')));

        $this->app->when(RatingService::class)
            ->needs(HttpClient::class)
            ->give(fn () => new HttpClient(config('services.ratings.url')));
    }

    public function boot()
    {
        //
    }
}
