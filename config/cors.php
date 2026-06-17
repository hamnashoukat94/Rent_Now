<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your settings for cross-origin resource sharing (CORS).
    | This determines what cross-origin operations may execute in browsers.
    | Adjust these settings as needed for your frontend and backend setup.
    |
    */

    'paths' => ['api/*', 'rentals/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Use .env value for flexibility, default to '*'
    'allowed_origins' => [env('CORS_ALLOWED_ORIGINS', '*')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Enable credentials so Sanctum cookies/session auth work
    'supports_credentials' => true,

];
