<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    /**
     * Socialite's Google+ auth driver.
     * The data here is using EcoLearnia-poc (ecoloearnia@gmail.com account)
     * To configure, go to https://console.developers.google.com
     */
    'google' => [
        'client_id' => '1010166817180-7cs8na79u93tldtgfcluq6dbl6841l4o.apps.googleusercontent.com',
        'client_secret' => getenv('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

    /**
     * Socialite's Google+ auth driver.
     * The data here is using Young-Suk's Facebook account
     * To configure, go to https://developers.facebook.com/apps
     */
    'facebook' => [
        'client_id' => '454433434750843',
        'client_secret' => getenv('FACEBOOK_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/auth/facebook/callback',
    ],

    /**
     * Socialite's Google+ auth driver.
     * The data here is using Young-Suk's personal LinkedIn account
     * To configure, go to https://www.linkedin.com/secure/developer?newapp=
     */
    'linkedin' => [
        'client_id' => '77gwr0gruwhovu',
        'client_secret' => getenv('LINKEDIN_CLIENT_SECRET'),
        'redirect' => 'http://localhost:8000/auth/linkedin/callback',
    ],

];
