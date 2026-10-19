<?php

$sites = [
    'main' => [
        'enabled' => env('HIMRISHTEY_ACCOUNT_MAIL_ENABLED', true),
        'mailer' => 'smtp',
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@himrishtey.com'),
        'name' => env('MAIL_FROM_NAME', 'Him Rishtey'),
        'brand' => 'HimRishtey',
        'support_address' => env('SUPPORT_MAIL_FROM_ADDRESS', 'support@himrishtey.com'),
        'url' => env('SITE1_APP_URL', 'https://himrishtey.com'),
    ],
];

foreach (['gallpakki' => 'GallPakki', 'devbhoomi' => 'DevbhoomiRishtey', 'dogririshtey' => 'DogriRishtey'] as $code => $brand) {
    $prefix = strtoupper($code).'_ACCOUNT_MAIL_';
    $sites[$code] = [
        'enabled' => env(strtoupper($code).'_ACCOUNT_MAIL_ENABLED', false),
        'mailer' => 'account_'.$code,
        'address' => env($prefix.'FROM_ADDRESS'),
        'name' => env($prefix.'FROM_NAME', $brand),
        'brand' => $brand,
        'support_address' => env(strtoupper($code).'_SUPPORT_MAIL_FROM_ADDRESS'),
        'url' => env(strtoupper($code).'_ACCOUNT_MAIL_URL'),
    ];
}

return ['sites' => $sites];
