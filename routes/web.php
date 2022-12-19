<?php declare(strict_types = 1);

use Carbon\Carbon;
use App\Helpers\RouteHelper;

/*
    |--------------------------------------------------------------------------
    | Set The Application start time
    |--------------------------------------------------------------------------
    |
 */

config(
    [
        'app.start' => microtime(true),
    ],
);

/*
    |--------------------------------------------------------------------------
    | Status Route
    |--------------------------------------------------------------------------
    |
 */

$rh = new RouteHelper($router, [], __FILE__);

$rh->addRoute([
    'method' => 'GET',
    'uri' => '/status',
    'action' => function () {
        // Return configuration variables in a non-production enviroment.
        if ('local' === config('app.env') || 'testing' === config('app.env')) {
            return response()->json(
                [
                    'status' => 'up',
                    'release' => config('app.release'),
                    'env' => config('app.env'),
                    'details' => config('app.env') . ' - ' . config('app.name') . ' - ' . Carbon::now()->format(
                        'd.m.Y',
                    ),
                ],
            );
        }

        // In a production enviroment, the route returns just the status field.
        return response()->json(
            [
                'status' => 'up',
                'release' => config('app.release'),
            ],
        );
    },
]);

// Only create these routes when running locally
if ('local' == config('app.env')) {
    $rh->addRoute([
        'method' => 'GET',
        'uri' => '/',
        'action' => function () {
            return view('errors.error');
        },
    ]);
}
