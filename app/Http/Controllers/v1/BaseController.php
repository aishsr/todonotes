<?php

declare(strict_types = 1);

namespace App\Http\Controllers\v1;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

use App\Http\Requests\BaseRequest;
use App\Http\Responses\v1\BaseResponse;
use Exception;

/**
 * Base Controller
 * All controllers should extend
 * this one.
 */
abstract class BaseController extends Controller
{
    /**
     * Return the BaseRequest or null associated with controller method.
     *
     * @param string $method The controller method being called.
     *
     * @throws InvalidArgumentException
     *
     * @return BaseRequest|null
     */
    abstract public static function getRequest(string $method);

    /**
     * Return BaseResponse associated with a method.
     *
     * @param string $method The controller method being called.
     *
     * @throws InvalidArgumentException
     *
     * @return BaseResponse
     */
    abstract public static function getResponse(string $method);

    /**
     * Return an array of error responses.
     * Where the key is the status code and value is a BaseResponse.
     *
     * @param string $method The controller method being called
     *
     * @throws InvalidArgumentException
     *
     * @return array<int=>BaseResponse>
     */
    abstract public static function getErrorResponse(string $method): array;

    /**
     * Define the services used by the this controller.
     *
     * @return void
     */
    abstract protected function defineServices(): void;

    /**
     * Constructor
     */
    final public function __construct()
    {
        $this->defineServices();
    }

    /**
     * Validate the request by its rules.
     *
     * @param Request $request
     * @param string $action
     *
     * @return array
     */
    final protected function validateRequest(Request $request, $action)
    {
        $rules = static::getRequest($action);

        if (is_null($rules)) {
            $rules = [];
        } else {
            $rules = static::getRequest($action)->rules();
        }
        $validated = $this->validate($request, $rules);

        return $validated;
    }

    /**
     * Throws an InvalidArgumentException
     *
     * @param string $method The requested method that is invalid
     * @param string $action The calling function
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    final protected static function invalidMethod($method, $action)
    {
        throw new InvalidArgumentException('Unknown method "' . $method . '" in function ' . $action);
    }
}
