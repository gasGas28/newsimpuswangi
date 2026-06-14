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

    // ...
    'recaptcha' => [
        'site_key'   => env('VITE_RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'satusehat' => [
        'auth_url'        => env('SATUSEHAT_AUTH_URL'),
        'fhir_url'        => env('SATUSEHAT_FHIR_URL'),
        'client_id'       => env('SATUSEHAT_CLIENT_ID'),
        'client_secret'   => env('SATUSEHAT_CLIENT_SECRET'),
        'organization_id' => env('SATUSEHAT_ORGANIZATION_ID'),
        'location_id'     => env('SATUSEHAT_LOCATION_ID'),
        'practitioner_id' => env('SATUSEHAT_PRACTITIONER_ID'),
        'practitioner_name' => env('SATUSEHAT_PRACTITIONER_NAME'),
        'location_name'   => env('SATUSEHAT_LOCATION_NAME'),
    ],

];
