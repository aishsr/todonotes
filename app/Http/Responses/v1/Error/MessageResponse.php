<?php declare(strict_types = 1);

namespace App\Http\Responses\v1\Error;

use App\Http\Responses\v1\BaseResponse;

class MessageResponse extends BaseResponse
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
     * @param mixed $data Internal data that requires formatting
     * @param array|null $validatedRequest IGNORED
     *
     * @return mixed
     */
    public function formatData($data, $validatedRequest)
    {
        return [
            'code' => $data['code'],
            'message' => $data['message'],
        ];
    }
}
