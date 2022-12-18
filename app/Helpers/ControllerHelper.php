<?php declare(strict_types = 1);

namespace App\Helpers;

use App\Http\Controllers\v1\BaseController;
use App\Http\Responses\v1\Error\ErrorResponse;
use App\Http\Responses\v1\Error\ValidationErrorResponse;
use App\Http\Responses\v1\BaseResponse;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ControllerHelper
{
    /**
     * Format Exception Response
     *
     * @param BaseController $controller
     * @param Exception $err
     * @param string $method
     * @param integer $defaultStatusCode
     *
     * @return BaseResponse
     */
    public static function formatExceptionResponse(BaseController $controller, Exception $err, $method, $defaultStatusCode = 500)
    {
        if ($err instanceof ValidationException) {
            $resp = new ValidationErrorResponse(422);
            $resp->generateResponse($err, []);

            return $resp;
        }
        $resp = $controller->getErrorResponse($method)[$defaultStatusCode];

        return $resp;
    }

    /**
     * Get Error Response
     *
     * @param array $statusCodes
     *
     * @return array
     */
    public static function getErrorResponse($statusCodes): array|ErrorResponse
    {
        $arr = [];

        foreach ($statusCodes as $code) {
            switch ($code) {
                case 400:
                    $tmp = new ErrorResponse(400);
                    $tmp->generateResponse(['code' => 400, 'message' => __('error.400')], []);

                    break;
                case 401:
                    $tmp = new ErrorResponse(401);
                    $tmp->generateResponse(['code' => 401, 'message' => __('error.401')], []);

                    break;
                case 404:
                    $tmp = new ErrorResponse(404);
                    $tmp->generateResponse(['code' => 404, 'message' => __('error.404')], []);

                    break;
                case 500:
                    $tmp = new ErrorResponse(500);
                    $tmp->generateResponse(['code' => 500, 'message' => __('error.500')], []);

                    break;
                case 501:
                    $tmp = new ErrorResponse(501);
                    $tmp->generateResponse(['code' => 501, 'message' => __('error.501')], []);

                    break;

                default:
                    $tmp = new ErrorResponse(500);
                    $tmp->generateResponse(['code' => 500, 'message' => __('error.500')], []);
            }
            $arr[$code] = $tmp;
        }

        return $arr;
    }
}
