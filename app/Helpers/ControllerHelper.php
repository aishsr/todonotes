<?php declare(strict_types = 1);

namespace App\Helpers;

use App\Http\Controllers\v1\BaseController;
use App\Http\Responses\v1\Error\ErrorResponse;
use App\Http\Responses\v1\Error\ValidationErrorResponse;
use App\Http\Responses\v1\BaseResponse;

use Exception;
use Illuminate\Validation\ValidationException;

class ControllerHelper
{
    /**
     * Format Exception into a response that is returned to the user
     *
     * @param BaseController $controller Controller class
     * @param Exception $error Exception that is thrown
     * @param string $method Endpoint method (ex: index, show, store, ...)
     * @param integer $defaultStatusCode Default HTTP status code
     *
     * @return BaseResponse
     */
    public static function formatExceptionResponse(BaseController $controller, Exception $error, $method, $defaultStatusCode = 500)
    {
        if ($error instanceof ValidationException) {
            $responseClass = new ValidationErrorResponse(422);

            return $responseClass->generateResponse($error, []);
        }

        return $controller->getErrorResponse($method)[$defaultStatusCode];
    }

    /**
     * Get Error Response
     *
     * @param array $statusCodes Array of error status codes for a route
     *
     * @return array
     */
    public static function getErrorResponse($statusCodes): array|ErrorResponse
    {
        $errorResponses = [];

        foreach ($statusCodes as $code) {
            $responseClass = new ErrorResponse($code);
            $responseClass->generateResponse(['code' => $code, 'message' => __("error.{$code}")], []);
            $errorResponses[$code] = $responseClass;
        }

        return $errorResponses;
    }
}
