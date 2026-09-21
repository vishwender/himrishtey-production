<?php

return [
    'runtime' => [
        'firebase_client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'firebase_private_key' => env('FIREBASE_PRIVATE_KEY'),
        'firebase_project_id' => env('FIREBASE_PROJECT_ID'),
        'nimbus_entity' => env('NIMBUS_ENTITY', '1701164189692214854'),
        'nimbus_password' => env('NIMBUS_PASSWORD'),
        'nimbus_sender' => env('NIMBUS_SENDER', 'HIMRMB'),
        'nimbus_registration_template' => env('NIMBUS_TEMPLATE', '1707166036739168867'),
        'nimbus_login_template' => env('NIMBUS_TEMPLATE', '1707166088646916717'),
        'nimbus_interest_template' => env('NIMBUS_TEMPLATE', '1707166254978193907'),
        'nimbus_callback_template' => env('NIMBUS_TEMPLATE', '1707166254945835455'),
        'nimbus_verification_template' => env('NIMBUS_TEMPLATE', '1707166036743902118'),
        'nimbus_username' => env('NIMBUS_USERNAME', 'himrishteybiz'),
        'razorpay_key' => env('RAZORPAY_KEY'),
        'razorpay_secret' => env('RAZORPAY_SECRET'),
        'vapid_private_key' => env('VAPID_PRIVATE_KEY'),
        'vapid_public_key' => env('VAPID_PUBLIC_KEY'),
    ],
];
