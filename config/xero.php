<?php

use Webfox\Xero\Oauth2CredentialManagers\FileStore;

return [

    'api_host' => 'https://api.xero.com/api.xro/2.0',

    /************************************************************************
     * Class used to store credentials.
     * Must implement OauthCredentialManager Interface
     ************************************************************************/
    'credential_store' => FileStore::class,

    'oauth' => [
        /************************************************************************
         * Client ID provided by Xero when registering your application
         ************************************************************************/
        'client_id'                  => env('XERO_CLIENT_ID', 'DF4F761BA96D4ACB835BA246A27C5C4D'),

        /************************************************************************
         * Client Secret provided by Xero when registering your application
         ************************************************************************/
        'client_secret'              => env('XERO_CLIENT_SECRET', 'ulqFSSXuYZctUneDXoaXOOtq-W0QYpy1-6QW2ih84QlljAII'),

        /************************************************************************
         * Webhook signing key provided by Xero when registering webhooks
         ************************************************************************/
        'webhook_signing_key'        => env('XERO_WEBHOOK_KEY', 'E8LVEJamo7nYagUZ+WOdYMtU4eH/LO5NrXaAN0BLBALnvQ1lf3dDkB8HSZb1xNuomJ6zlMk3U/uYi2OUDpptog=='),

        /************************************************************************
         * Then scopes you wish to request access to on your token
         * https://developer.xero.com/documentation/oauth2/scopes
         ************************************************************************/
         'scopes'                     => [
            'openid',
            'email',
            'profile',
            'offline_access',
            'accounting.settings',
            'accounting.contacts',
            'accounting.transactions'
        ],


        /************************************************************************
         * Url to redirect to upon success
         ************************************************************************/
        'redirect_on_success'        => 'xero.sigin',

        /************************************************************************
         * Url for Xero to redirect to upon granting access
         * Unless you wish to change the default behaviour you should not need to
         * change this
         ************************************************************************/
        'redirect_uri'               => 'xero.auth.callback',

        /************************************************************************
         * If the 'redirect_uri' is not a route name, but rather a full url set
         * this to true and we won't wrap it in `route()`
         ************************************************************************/
        'redirect_full_url'          => false,

        /************************************************************************
         * Urls for Xero's Oauth integration, you shouldn't need to change these
         ************************************************************************/
        'url_authorize'              => 'https://login.xero.com/identity/connect/authorize',
        'url_access_token'           => 'https://identity.xero.com/connect/token',
        'url_resource_owner_details' => 'https://api.xero.com/api.xro/2.0/Organisation',
    ],

];
