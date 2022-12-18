<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Rules\SortRule;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ValidationHelper
{
    /**
     * Validate the response spec, filter out anything not in it
     *
     * @param array $data
     * @param array $responseSpec
     * @param mixed $formattedResponse
     *
     * @return array
     */
    public static function filterValidateData($formattedResponse, $responseSpec)
    {
        $validator = Validator::make($formattedResponse, $responseSpec);

        if ($validator->fails()) {
            $errors = $validator->errors();
            dd($errors);
            $message = 'The response spec failed to validate.';

            throw new Exception($message);
        }

        return $validator->validated();
    }
}
