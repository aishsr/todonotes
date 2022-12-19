<?php declare(strict_types = 1);

/**
 * ! DO NOT MOVE OR RENAME THIS FILE
 *
 * PACKAGE CONFIG - Laravel/Lumen
 *
 * Laravel/Lumen default app configuration values.
 */

return [

    /**
     * Default Filesystem Disk
     *
     * Accepted Values: [local, public, base, seed_data, s3]
     */
    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /**
     * Each of the Filesystem Disks available.
     * Supported Drivers: [local, ftp, s3, rackspace]
     */
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'base' => [
            'driver' => 'local',
            'root' => base_path(),
        ],

        'seed_data' => [
            'driver' => 'local',
            'root' => base_path('database/data'),
        ],
    ],
];
