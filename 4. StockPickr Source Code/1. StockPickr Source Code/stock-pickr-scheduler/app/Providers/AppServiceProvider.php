<?php

namespace App\Providers;

use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(CompanyProviderService::class)
            ->needs('$url')
            ->give(config('services.company-provider.url'));

        $this->app->when(RedisService::class)
            ->needs('$consumerMode')
            ->give(config('redis.consumer_mode'));
    }
}
