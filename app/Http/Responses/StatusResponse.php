<?php declare(strict_types = 1);

namespace App\Http\Responses;

use App\Http\Responses\v1\BaseResponse;

class StatusResponse extends BaseResponse
{
    /**
     * The required response specification validation rules.
     *
     * @return array
     */
    public static function requiredSpec(): array
    {
        return [
            'status' => 'required|string',
            'release' => 'required|string',
            'env' => 'string',
            'details' => 'string',
        ];
    }

    /**
     * The response specification validation rules.
     * Should include `this->requiredSpec()`.
     *
     * @param array|null $validatedRequest Validated request values from the user
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
     * @param mixed $data Internal data that requires formatting
     * @param array|null $validatedRequest Validated request values from the user
     *
     * @return mixed
     */
    public function formatData($data, $validatedRequest)
    {
        return $data;
    }
}
