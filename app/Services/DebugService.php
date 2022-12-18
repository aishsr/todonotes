<?php declare(strict_types = 1);

namespace App\Services;

use Carbon\Carbon;

class DebugService extends BaseService
{
    /**
     * getIndexResults
     *
     * @param array $validated
     *
     * @return array
     */
    public function getStatus()
    {
        // Return configuration variables in a non-production enviroment.
        if ('local' === config('app.env') || 'testing' === config('app.env')) {
            return [
                'status' => 'up',
                'release' => config('app.release'),
                'env' => config('app.env'),
                'details' => config('app.env') . ' - ' . config('app.name') . ' - ' . Carbon::now()->format(
                    'd.m.Y',
                ),
            ];
        }

        // In a production enviroment, the route returns just the status field.
        return [
            'status' => 'up',
            'release' => config('app.release'),
        ];
    }

    /**
     * getIndexResults
     *
     * @param array $validated
     *
     * @return array
     */
    public function getXDebugPage()
    {
        ob_start();
        \xdebug_info();

        return ob_get_clean();
    }
}
