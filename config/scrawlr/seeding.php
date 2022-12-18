<?php declare(strict_types = 1);

/**
 * Seeding
 *
 * Seeding configuration.
 */

return [
    // TODO: Write up notes on this environment variable
    'base_seed_size' => env('TEST_DATASET_SIZE', 50),

    // TODO: Write up notes on this environment variable
    'raw_seed_size' => env('TEST_DATASET_RAW_SEED_SIZE', 5000),

    // TODO: Write up notes on this environment variable
    'use_real_text' => env('TEST_DATASET_USE_REAL_TEXT', false),
];
