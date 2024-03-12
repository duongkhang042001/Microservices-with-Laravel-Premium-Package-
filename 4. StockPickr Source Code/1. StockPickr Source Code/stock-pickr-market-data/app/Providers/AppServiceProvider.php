<?php

namespace App\Providers;

use App\Services\RedisService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->when(RedisService::class)
            ->needs('$consumerMode')
            ->give(config('redis.consumer_mode'));
    }
}
