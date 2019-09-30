<?php

return [
    'current_version' => 'v1',
    'prefix' => 'api',
    'auth' => [
        'reset_password' => [
            'token_timeout' => 60,
            'url' => 'client/vue/reset-password/%s',
        ],
        'token_lifetime_hour' => [
            'access_token' => 1,
            'refresh_token' => 48,
        ],
    ],
];
