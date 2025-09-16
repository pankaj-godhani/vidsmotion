<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'onrender' => [
        'key' => env('ONRENDER_API_KEY', ''),
        'base_url' => env('ONRENDER_BASE_URL', 'https://it2v-api.onrender.com/api/v1'),
        'header' => env('ONRENDER_HEADER', 'X-API-Key'), // or Authorization
        'auth_scheme' => env('ONRENDER_AUTH_SCHEME', null), // e.g. Bearer
        'file_field' => env('ONRENDER_FILE_FIELD', 'file'),
        'method' => env('ONRENDER_METHOD', 'POST'),
    ],

];
