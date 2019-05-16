<?php
# https://day.app/2018/06/bark-server-document/

return [
    'bark' => [
        'admin'    => env('BARK_KEY_BLUESKY', ''),
    ],
    'sc' => [
        'admin' => env('SC_KEY_BLUESKY', ''),
        'manager' => env('SC_KEY_MONIKA', ''),
    ],
];
