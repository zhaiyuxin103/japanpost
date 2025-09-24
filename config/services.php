<?php

declare(strict_types=1);

return [
    'japanpost' => [
        'client_id'  => env('JAPANPOST_CLIENT_ID'),
        'secret_key' => env('JAPANPOST_SECRET_KEY'),
        'base_uri'   => env('JAPANPOST_BASE_URI', 'https://api.da.pf.japanpost.jp/'),
    ],
];
