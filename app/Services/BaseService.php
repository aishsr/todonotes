<?php declare(strict_types = 1);

namespace App\Services;

use Illuminate\Http\Request;
use Exception;

abstract class BaseService
{
    /**
     * The default amount of results to return, if not per page is specified.
     *
     * @var integer
     */
    public static $defaultPerPage = 25;

    /**
     * Return the number to paginate results by
     *
     * @param array $request
     *
     * @return boolean
     */
    final protected function getPaginateBy(array $validatedRequest)
    {
        // TODO(Jon): redo this function, as it should not allow for user values

        $perPage = self::$defaultPerPage;
        $page = 1;

        if (array_key_exists('per_page', $validatedRequest)) {
            $perPage = $validatedRequest['per_page'];
        }

        if (array_key_exists('page', $validatedRequest)) {
            $page = $validatedRequest['page'];
        }

        return [
            'perPage' => $perPage,
            'page' => $page,
        ];
    }
}
