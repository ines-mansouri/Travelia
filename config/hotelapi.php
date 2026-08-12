<?php

return [
    'liteapi' => [
        'key' => env('LITEAPI_KEY'),
        'base_url' => env('LITEAPI_BASE_URL', 'https://api.liteapi.travel/v3.0'),
    ],

    'amadeus' => [
        'client_id' => env('AMADEUS_API_KEY'),
        'client_secret' => env('AMADEUS_API_SECRET'),
        'base_url' => env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com'),
    ],
];
