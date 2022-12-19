<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Controllers\v1\BaseController as BaseController;
use App\Helpers\ControllerHelper;
use App\Services\DebugService;
use App\Http\Requests\v1\BaseRequest;
use App\Http\Responses\StatusResponse;
use App\Http\Responses\HTMLResponse;
use Illuminate\Http\Request;
use Exception;
use InvalidArgumentException;

class DebugController extends BaseController
{
    private DebugService $debugService;

    /**
     * Return the BaseRequest or null associated with controller method.
     *
     * @param string $method The controller method being called.
     *
     * @throws InvalidArgumentException
     *
     * @return BaseRequest|null
     */
    public static function getRequest($method)
    {
        switch ($method) {
            case 'status':
                return null;
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

    /**
     * Return BaseResponse associated with a method.
     *
     * @param string $method The controller method being called.
     *
     * @throws InvalidArgumentException
     *
     * @return BaseResponse
     */
    public static function getResponse($method)
    {
        switch ($method) {
            case 'status':
                return new StatusResponse(200);
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

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
    public static function getErrorResponse($method):array
    {
        switch ($method) {
            case 'status':
                return ControllerHelper::getErrorResponse([500]);
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

    /**
     * Define the services used by the this controller.
     *
     * @return void
     */
    protected function defineServices(): void
    {
        $this->debugService = new DebugService();
    }

    /**
     * Get the status of the running application
     *
     * @param Request $request
     *
     * @return BaseResponse
     */
    public function status(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);
            $data = $this->debugService->getStatus();

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__);
        }
    }
}
