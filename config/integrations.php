<?php

return [
    'enabled' => [
        'flights' => env('INTEGRATIONS_FLIGHTS_ENABLED', true),
        'hotels' => env('INTEGRATIONS_HOTELS_ENABLED', true),
        'destinations' => env('INTEGRATIONS_DESTINATIONS_ENABLED', true),
    ],

    'default_flight_provider' => env('FLIGHT_PROVIDER', 'amadeus'),
    'flight_fallback_chain' => array_filter(explode(',', env(
        'FLIGHT_FALLBACK_CHAIN',
        'amadeus,kiwi,rapidapi'
    ))),

    'default_hotel_provider' => env('HOTEL_PROVIDER', 'liteapi'),
    'hotel_fallback_chain' => array_filter(explode(',', env(
        'HOTEL_FALLBACK_CHAIN',
        'liteapi,local'
    ))),

    'cache_store' => env('INTEGRATIONS_CACHE_STORE', env('CACHE_DRIVER', 'file')),

    'rate_limits' => [
        'amadeus' => ['requests' => 10, 'per_seconds' => 1],
        'kiwi' => ['requests' => 5, 'per_seconds' => 1],
        'rapidapi' => ['requests' => 30, 'per_seconds' => 60],
        'liteapi' => ['requests' => 20, 'per_seconds' => 60],
        'nominatim' => ['requests' => 1, 'per_seconds' => 1],
    ],

    'retry' => [
        'max_attempts' => 3,
        'base_delay_ms' => 1000,
        'retry_on' => [429, 500, 502, 503, 504],
    ],

    'cache_ttl' => [
        'flight_search' => 900,
        'flight_airports' => 86400,
        'hotel_search' => 600,
        'destination_place' => 604800,
        'oauth_token' => 3300,
    ],
];
