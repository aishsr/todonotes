<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Rules\SortRule;

use Exception;
use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    /**
     * Validate the response spec, filter out anything not in it
     *
     * @param array $formattedResponse Formatted data to send as response
     * @param array $responseSpec Response body validation rules
     *
     * @return array
     */
    public static function filterValidateData(array $formattedResponse, array $responseSpec): array
    {
        $validator = Validator::make($formattedResponse, $responseSpec);

        if ($validator->fails()) {
            throw new Exception('The response spec failed to validate.');
        }

        return $validator->validated();
    }
}
