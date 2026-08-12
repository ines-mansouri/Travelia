<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TravelCache
{
    /**
     * Cache results safely with standardized tags and hashed keys.
     */
    public static function remember(string $type, array $params, int $ttlSeconds, callable $callback)
    {
        ksort($params);
        $key = "travelia:search:{$type}:" . md5(json_encode($params));

        // Laravel Cache tags are supported by Redis store
        if (config('cache.default') === 'redis') {
            return Cache::tags(['travel_search', $type])->remember($key, $ttlSeconds, $callback);
        }

        return Cache::remember($key, $ttlSeconds, $callback);
    }
}
