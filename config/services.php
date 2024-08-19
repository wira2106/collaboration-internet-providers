<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
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

    'key_map' => 'AIzaSyAJRyIHISCimoNj8hkhQiSpqnuKS5nju8w',
    'version' => '1',
    'briva_key' => 'zmyqEO0HAWmkisNNURXO3m0XP5iiYJ2ZIAQeNMgzWlutTLeyoDDdLBRef7W4',
    'briva_production' => false,
    'briva_url' => [
        'sandbox' => 'https://briva.jinom.net/api/briva/sandbox',
        'production'=> 'https://briva.jinom.net/api/briva/production'
    ],

];
