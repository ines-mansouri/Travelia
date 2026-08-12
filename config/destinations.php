<?php

return [
    'nominatim' => [
        'base_url' => env('NOMINATIM_BASE_URL', 'https://nominatim.openstreetmap.org'),
        'user_agent' => env('NOMINATIM_USER_AGENT', 'Travelia/1.0 (contact@travelia.tn)'),
    ],

    'google_places' => [
        'key' => env('GOOGLE_PLACES_API_KEY'),
        'base_url' => env('GOOGLE_PLACES_BASE_URL', 'https://maps.googleapis.com/maps/api/place'),
    ],

    'wikipedia' => [
        'base_url' => env('WIKIPEDIA_BASE_URL', 'https://en.wikipedia.org/api/rest_v1'),
    ],
];
