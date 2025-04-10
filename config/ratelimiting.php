<?php

return [
    'api' => [
        'limit' => env('API_RATE_LIMIT', 60),
        'expires' => 60,
    ],
    'auth' => [
        'limit' => env('AUTH_RATE_LIMIT', 10),
        'expires' => 60,
    ],
    'weather' => [
        'limit' => env('WEATHER_RATE_LIMIT', 30),
        'expires' => 60,
    ],
];