<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_KEY_SECRET'),
        'region' => env('SES_REGION'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET')
    ],

    'recaptcha' => [
        'on_live' => env('RECAPTCHA_ON_LIVE', false),

        'score' => env('RECAPTCHA_SCORE', 0.5),

        'v2' => [
            'site_key' => env('RECAPTCHA_SITE_KEY_V2'),

            'secret_key' => env('RECAPTCHA_SECRET_KEY_V2')
        ],

        'v3' => [
            'site_key' => env('RECAPTCHA_SITE_KEY_V3'),

            'secret_key' => env('RECAPTCHA_SECRET_KEY_V3')
        ]
    ],

    'mailchimp' => [
        'api' => env('MAILCHIMP_API_KEY'),
        // To find the value for the server parameter used in mailchimp.setConfig,
        // log into your Mailchimp account and look at the URL in your browser.
        // You’ll see something like https://us19.admin.mailchimp.com/;
        // the us19 part is the server prefix. Note that your specific value may be different.
        'server_prefix' => env('MAILCHIMP_SERVER_PREFIX'),
        // To find current list id ( Audience ID )https://mailchimp.com/help/find-audience-id/
        'list_id' => env('MAILCHIMP_LIST_ID')
    ],

    'xero' => [
        'exclusive_branding_theme_id' => env('XERO_EXCLUSIVE_BRANDING_THEME_ID'),
        'inclusive_branding_theme_id' => env('XERO_INCLUSIVE_BRANDING_THEME_ID'),
        'payment_account_id' => env('XERO_PAYMENT_ACCOUNT_ID'),
    ],
];