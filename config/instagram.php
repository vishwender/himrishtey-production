<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Instagram Feed
    |--------------------------------------------------------------------------
    |
    | Keep the API version configurable. Use the version shown for your Meta
    | app rather than hard-coding it in application code.
    |
    */
    'enabled' => env('INSTAGRAM_FEED_ENABLED', true),

    'base_url' => env('INSTAGRAM_API_BASE_URL', 'https://graph.instagram.com'),
    'api_version' => env('INSTAGRAM_API_VERSION'),

    'cache_minutes' => (int) env('INSTAGRAM_CACHE_MINUTES', 60),
    'fallback_cache_hours' => (int) env('INSTAGRAM_FALLBACK_CACHE_HOURS', 24),
    'timeout_seconds' => (int) env('INSTAGRAM_TIMEOUT_SECONDS', 8),
    'limit' => (int) env('INSTAGRAM_FEED_LIMIT', 6),
];
