<?php declare(strict_types = 1);

/**
 * ! DO NOT MOVE OR RENAME THIS FILE
 *
 * PACKAGE CONFIG - Laravel/Lumen
 *
 * Laravel/Lumen default logging configuration values.
 */

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /**
     * Default Log Channel
     *
     * Accepted Values: [stack, single, daily, slack, syslog, errorlog, monolog, custom]
     */
    'default' => env('LOG_CHANNEL', 'stack'),

    /**
     * Each of the Logging channels available.
     *
     * Accepted Values: [stack, single, daily, slack, syslog, errorlog, monolog, custom]
     */
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 4,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Lumen Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => ['stream' => 'php://stderr'],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],
    ],
];
