<?php

declare(strict_types = 1);

namespace App\Http\Responses\v1\Error;

use App\Http\Responses\v1\BaseResponse;

use Illuminate\Validation\ValidationException;

class ValidationErrorResponse extends BaseResponse
{
    /**
     * The required response specification validation rules.
     *
     * @return array
     */
    public static function requiredSpec(): array
    {
        return [
            'code' => 'required|integer',
            'message' => 'required|string|min:1',
            'errors' => 'present|array',
        ];
    }

    /**
     * The response specification validation rules.
     * is equal to `this->requiredSpec()`.
     *
     * @param array $validatedRequest IGNORED
     *
     * @return array
     */
    public static function responseSpec($validatedRequest): array
    {
        return static::requiredSpec();
    }

    /**
     * Format internal data into required API specification.
     *
     * @param ValidationException $data Internal data that requires formatting
     * @param array|null $validatedRequest IGNORED in this reesponse class
     * @param mixed $valException validation exception to throw
     *
     * @return mixed
     */
    public function formatData($valException, $validatedRequest)
    {
        return [
            'code' => 422,
            'message' => 'Validation error with your request',
            'errors' => $valException->validator->errors()->messages(),
        ];
    }
}
