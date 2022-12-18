<?php

declare(strict_types = 1);

use App\Helpers\RouteHelper;
use App\Helpers\ValidationHelper;

/**
 * Note Routes
 *
 * This contains all the routes under `notes`.
 */
$attributes = [
    'prefix' => 'notes',
    'namespace' => 'v1',
];
$router->group(
    $attributes,
    function () use ($router, $attributes) {
        $rh = new RouteHelper($router, $attributes, __FILE__);
        $rh->logStartRoutes();

        $rh->addRoute([
            'method' => 'GET',
            'uri' => '/',
            'action' => [
                'as' => 'notes.index',
                'uses' => 'NoteController@index',
            ],
        ]);

        $rh->addRoute([
            'method' => 'GET',
            'uri' => '/{id}',
            'action' => [
                'as' => 'notes.show',
                'uses' => 'NoteController@show',
            ],
        ]);

        $rh->addRoute([
            'method' => 'POST',
            'uri' => '/',
            'action' => [
                'as' => 'notes.store',
                'uses' => 'NoteController@store',
            ],
        ]);

        $rh->addRoute([
            'method' => 'PUT',
            'uri' => '/{id}',
            'action' => [
                'as' => 'notes.update',
                'uses' => 'NoteController@update',
            ],
        ]);

        $rh->addRoute([
            'method' => 'DELETE',
            'uri' => '/{id}',
            'action' => [
                'as' => 'notes.delete',
                'uses' => 'NoteController@delete',
            ],
        ]);

        $rh->logEndRoutes();
    }
);
