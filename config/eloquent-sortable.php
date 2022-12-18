<?php declare(strict_types = 1);

/**
 * ! DO NOT MOVE OR RENAME THIS FILE
 *
 * PACKAGE CONFIG - Eloquent Sortable
 *
 * Sortable behaviour for Eloquent models.
 *
 * ? Documentation: https://github.com/spatie/eloquent-sortable
 */

return [

    // Which column will be used as the order column.
    'order_column_name' => 'order_column',

    /**
     * Define if the models should sort when creating.
     * When true, the package will automatically assign the highest order number to a new mode
     */
    'sort_when_creating' => true,
];
