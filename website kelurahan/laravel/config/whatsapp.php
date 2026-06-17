<?php

return [
    'driver' => env('WHATSAPP_DRIVER', 'link'),

    'waha' => [
        'base_url' => env('WAHA_BASE_URL', 'http://waha:3000'),
        'api_key' => env('WAHA_API_KEY'),
        'session' => env('WAHA_SESSION', 'default'),
    ],
];
