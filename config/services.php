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

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'github' => [
        'client_id' => env('GIT_HUB_CLIENT_ID'),
        'client_secret' => env('GIT_HUB_CLIENT_SECRET'),
        'redirect' => env('APP_URL', 'https://abc.dev').env('GIT_HUB_CLIENT_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect' => env('APP_URL', 'https://abc.dev').env('FACEBOOK_REDIRECT_URI'),
        'page-token' => env('FACEBOOK_PAGE_TOKEN', 'YOUR PAGE TOKEN HERE'),
        'version' => env('FACEBOOK_GRAPH_API_VERSION', '4.0'),
    ],

    'weixin' => [
        'client_id' => env('WEIXIN_KEY'),
        'client_secret' => env('WEIXIN_SECRET'),
        'redirect' => env('APP_URL', 'https://abc.dev').env('WEIXIN_REDIRECT_URI'),
        // 这一行配置非常重要，必须要写成这个地址。
        'auth_base_uri' => 'https://open.weixin.qq.com/connect/qrconnect',
    ],
    'wechat' => [
        'appid' => env('WEIXIN_KEY'),
        'appsecret' => env('WEIXIN_SECRET'),
    ],
    'twilio' => [
        'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'from' => env('TWILIO_FROM'), // optional
    ],
];
