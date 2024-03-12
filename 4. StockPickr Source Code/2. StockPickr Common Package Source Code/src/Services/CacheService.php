<?php

namespace StockPickr\Common\Services;

use Illuminate\Support\Facades\Cache;
use Closure;

class CacheService
{
    public const FOR_A_WEEK = 7 * 24 * 60 * 60;
    public const FOR_A_DAY = 24 * 60 * 60;
    public const FOR_AN_HOUR = 60 * 60;
    public const FOR_TEN_MINUTES = 10 * 60;

    public function getOrRemember(string $key, Closure $callback, ?int $ttl = null): mixed
    {
        if ($ttl === null) {
            $ttl = static::FOR_A_WEEK;
        }

        if ($cached = Cache::get($key)) {
            return $cached;
        }

        return Cache::remember($key, $ttl, $callback);
    }
}
