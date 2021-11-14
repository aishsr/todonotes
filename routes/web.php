<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/** @var \Laravel\Lumen\Routing\Router $router */

// header('Access-Control-Allow-Origin:  *');
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get(
    '/status',
    function () {
        return response()->json(
            [
                'status' => 'up',
                'release' => 'local',
                'env' => 'local',
                'details' => 'local - onboarding-empty: to_do_notes application - ' . Carbon::now()->format(
                    'd.m.Y',
                ),
            ],
        );

    },
);

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'users'], function () use ($router) {
        // create a user
        $router->post('/', ['uses' => 'UserController@create_user']);

        // authenticate user
        $router->get('/', ['uses' => 'UserController@authenticate']);

    });


    $router->group(['prefix' => 'todonotes', 'middleware' => 'auth'], function () use ($router) {

        // create a todo note
        $router->post('/', ['uses' => 'ToDoNoteController@create_todonote']);

        // delete a todo note
        $router->delete('/{id}', ['uses' => 'ToDoNoteController@delete']);

        // mark todo note as complete
        $router->put('/complete/{id}', ['uses' => 'ToDoNoteController@update_complete']);

        // mark todo note as incomplete
        $router->put('/incomplete/{id}', ['uses' => 'ToDoNoteController@update_incomplete']);

        // list all todo notes for logged in user
        $router->get('/',  ['uses' => 'ToDoNoteController@showAllToDoNotesForCurrentUser']);

        // list all todo notes for arbitrary user
        $router->get('/{userid}', ['uses' => 'ToDoNoteController@showAllToDoNotesForGivenUser']);

}   );

});
