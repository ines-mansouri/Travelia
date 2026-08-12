<?php

return [
    'rapidapi_key' => env('RAPIDAPI_KEY'),
    'rapidapi_host' => env('RAPIDAPI_HOST', 'sky-scrapper.p.rapidapi.com'),
    'base_url' => env('RAPIDAPI_BASE_URL', 'https://sky-scrapper.p.rapidapi.com'),

    'amadeus' => [
        'client_id' => env('AMADEUS_API_KEY'),
        'client_secret' => env('AMADEUS_API_SECRET'),
        'base_url' => env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com'),
    ],

    'kiwi' => [
        'key' => env('KIWI_API_KEY'),
        'base_url' => env('KIWI_BASE_URL', 'https://tequila-api.kiwi.com'),
    ],
];
