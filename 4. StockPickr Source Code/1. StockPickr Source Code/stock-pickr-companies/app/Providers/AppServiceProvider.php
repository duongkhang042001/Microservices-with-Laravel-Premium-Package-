<?php

namespace App\Providers;

use App\Services\RedisService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(RedisService::class)
            ->needs('$consumerMode')
            ->give(config('redis.consumer_mode'));
    }
}
