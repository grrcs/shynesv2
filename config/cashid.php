<?php

return [
    'client_id' => env('CASH_ID_CLIENT_ID', ''),
    'api_key' => env('CASH_ID_API_KEY', env('CASH_ID_CLIENT_ID', '')),
    'secret_key' => env('CASH_ID_SECRET_KEY', ''),
    'webhook_secret' => env('CASH_ID_WEBHOOK_SECRET', ''),
    'mode' => env('CASH_ID_MODE', 'sandbox'),

    'urls' => [
        'sandbox' => [
            'payment' => 'https://sandbox-api.cashin.co.id/pg/fo/payment',
            'status' => 'https://sandbox-api.cashin.co.id/pg/fo/payment/status',
        ],
        'production' => [
            'payment' => 'https://api.cashin.co.id/pg/fo/payment',
            'status' => 'https://api.cashin.co.id/pg/fo/payment/status',
        ],
    ],

    'callback_urls' => [
        'success' => '/payment/cashid/success',
        'cancel' => '/payment/cashid/cancel',
        'webhook' => '/api/payment/cashid/webhook',
    ],

    'payment_channel' => env('CASH_ID_PAYMENT_CHANNEL', 'QRIS'), // Legacy single channel
    'payment_channels' => [
        'QRIS' => 'QRIS',
        'VA' => 'VA',
    ],

    'duration' => env('CASH_ID_DURATION', 1440),
];
