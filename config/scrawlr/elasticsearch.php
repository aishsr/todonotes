<?php declare(strict_types = 1);
/**
 * elasticsearch
 *
 * Config for elasticsearch service
 */

return [
    // elastic search host name (from env)
    'hosts' => [
        'http://' . env('APP_NAME') . '_elasticsearch:' . env('ELASTICSEARCH_PORT'),
    ],

    // elastic search connection username and password (from env)
    'username' => 'elastic',
    'password' => env('ELASTICSEARCH_PASSWORD'),
];
