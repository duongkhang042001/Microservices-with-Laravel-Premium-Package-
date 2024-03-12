<?php

namespace App\Providers;

use App\Services\WarehouseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(WarehouseService::class)
            ->needs('$apiUrl')
            ->give(config('services.warehouse.url'));
    }
}
