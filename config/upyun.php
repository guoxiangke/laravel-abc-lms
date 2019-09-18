<?php

return [
    'bucket'        => env('UPYUN_BUCKET_NAME'),
    'operator'      => env('UPYUN_OPERATOR_NAME'),
    'password'      => env('UPYUN_OPERATOR_PASSWORD'),
    'domain'        => env('UPYUN_DOMAIN'),
    'protocol'     => env('UPYUN_DOMAIN_HTTP'),
    'token'=>[
        'sms' =>  env('UPYUN_SMS_TOKEN'),
    ],
];
