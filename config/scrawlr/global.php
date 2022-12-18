<?php declare(strict_types = 1);

/**
 * Global
 *
 * Custom global application information.
 */

return [

    // Public organization name
    'org_name' => env('APP_ORG_NAME', 'Scrawlr'),

    // Public organization URL
    'org_url' => env('APP_ORG_URL', 'https://corporate.scrawlr.com'),

    // The app release version
    'release' => env('APP_RELEASE', 'local'),

    /**
     * This APP_SSL_STATUS is used to indicate whether application should use
     * secure or non-secure formatted URLs.
     */
    'ssl_status' => env('APP_SSL_STATUS', true),
];
