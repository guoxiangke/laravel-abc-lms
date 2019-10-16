<?php

// https://day.app/2018/06/bark-server-document/

return [
    'bark' => [
        'admin'    => env('BARK_KEY_BLUESKY', ''),
        'ipad'     => env('BARK_KEY_IPAD', ''),
    ],
    'sc' => [
        'admin' => env('SC_KEY_BLUESKY', ''),
        'manager' => env('SC_KEY_MONIKA', ''),
    ],
    'test_user' => array_map('trim', explode(',', env('CLASS_RECORDS_NOTIFICATION', 1))),
];
