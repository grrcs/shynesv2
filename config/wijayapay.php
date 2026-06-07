<?php

return [
    'code_merchant' => env('WIJAYAPAY_CODE_MERCHANT', ''),
    'api_key' => env('WIJAYAPAY_API_KEY', ''),

    'base_url' => env('WIJAYAPAY_BASE_URL', 'https://wijayapay.com'),

    'callback_url' => env('WIJAYAPAY_CALLBACK_URL', '/payment/wijayapay/callback'),

    'payment_channel' => env('WIJAYAPAY_PAYMENT_CHANNEL', 'QRIS'),

    'payment_channels' => [
        'QRIS' => 'QRIS',
        'BRIVA' => 'BRI Virtual Akun',
        'BCAVA' => 'BCA Virtual Akun',
        'BNIVA' => 'BNI Virtual Akun',
        'BSIVA' => 'BSI Virtual Akun',
        'MANDIRIVA' => 'MANDIRI Virtual Akun',
        'PERMATAVA' => 'PERMATA Virtual Akun',
        'MAYBANKVA' => 'MAYBANK Virtual Akun',
        'MUAMALATVA' => 'Muamalat Virtual Akun',
        'CIMBVA' => 'CIMB Virtual Akun',
        'DANAMONVA' => 'DANAMON Virtual Akun',
        'BNCVA' => 'BNC Virtual Akun (NEO)',
        'OCBCVA' => 'OCBC Virtual Akun',
        'INDOMARET' => 'Indomaret Retail',
        'ALFAMART' => 'Alfamart Retail',
    ],
];
