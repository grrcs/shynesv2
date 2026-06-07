<?php

return [
    'slug' => env('PAKASIR_SLUG', 'i-dont-know'),
    'api_key' => env('PAKASIR_API_KEY', ''),
    'base_url' => env('PAKASIR_BASE_URL', 'https://app.pakasir.com'),
    'callback_url' => env('PAKASIR_CALLBACK_URL', '/payment/pakasir/callback'),
];
