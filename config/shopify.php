<?php

return [
    'store_url' => env('SHOPIFY_STORE_URL'),
    'api'       => [
        'version' => env('SHOPIFY_API_VERSION'),
        'key'     => env('SHOPIFY_ADMIN_API_KEY'),
        'token'   => env('SHOPIFY_ADMIN_API_TOKEN')
    ],
];
