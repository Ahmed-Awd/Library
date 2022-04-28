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
    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
        'android_channel_id' => env('ANDROID_CHANNEL_ID')
    ],
    'onesignaldriver' => [
        'app_id' => env('DRIVER_ONESIGNAL_APP_ID'),
        'rest_api_key' => env('DRIVER_ONESIGNAL_REST_API_KEY'),
        'android_channel_id' => env('DRIVER_ANDROID_CHANNEL_ID')
    ],
    'onesignalcustomer' => [
        'app_id' => env('CUSTOMER_ONESIGNAL_APP_ID'),
        'rest_api_key' => env('CUSTOMER_ONESIGNAL_REST_API_KEY'),
        'android_channel_id' => env('CUSTOMER_ANDROID_CHANNEL_ID')
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'bambora' => [
        'access_token' => env('BAMBORA_ACCESS_TOKEN'),
        'secret_token' => env('BAMBORA_SECRET_TOKEN'),
        'merchant_number' => env('BAMBORA_MERCHANT_NUMBER'),
    ],
    'gmaps' => [
        'key' => env('GMAPS_KEY'),
    ],
];
