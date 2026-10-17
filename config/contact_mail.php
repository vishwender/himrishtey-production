<?php

$sites = [
    'main' => [
        'enabled' => env('HIMRISHTEY_CONTACT_MAIL_ENABLED', true),
        'mailer' => 'support',
        'address' => env('SUPPORT_MAIL_FROM_ADDRESS', 'support@himrishtey.com'),
        'name' => env('SUPPORT_MAIL_FROM_NAME', 'Him Rishtey'),
        'brand' => 'HimRishtey',
        'url' => env('SITE1_APP_URL', 'https://himrishtey.com'),
    ],
];

foreach (['gallpakki' => 'GallPakki', 'devbhoomi' => 'DevbhoomiRishtey', 'dogririshtey' => 'DogriRishtey'] as $code => $brand) {
    $prefix = strtoupper($code).'_SUPPORT_MAIL_';
    $sites[$code] = [
        'enabled' => env(strtoupper($code).'_CONTACT_MAIL_ENABLED', false),
        'mailer' => 'support_'.$code,
        'address' => env($prefix.'FROM_ADDRESS'),
        'name' => env($prefix.'FROM_NAME', $brand),
        'brand' => $brand,
        'url' => env(strtoupper($code).'_CONTACT_MAIL_URL'),
    ];
}

return ['sites' => $sites];
