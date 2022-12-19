<?php declare(strict_types = 1);

namespace App\Http\Responses\v1\Note;

use App\Http\Responses\v1\BaseResponse;
use App\Helpers\ValidationHelper;

class NoteResponse extends BaseResponse
{
    /**
     * The required response specification validation rules.
     *
     * @return array
     */
    public static function requiredSpec(): array
    {
        return [];
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
        return [
            'data' => 'required|array',
            'data.uuid' => 'string',
            'data.userid' => 'string',
            'data.content' => 'string',
            'data.completion_time' => 'nullable|date',
            'data.created_at' => 'date',
            'data.updated_at' => 'date',
        ];
    }

    /**
     * Format internal data into required API specification.
     *
     * @param array $data Internal data that requires formatting
     * @param array|null $validatedRequest Validated request values from the user
     *
     * @return mixed
     */
    public function formatData($data, $validatedRequest)
    {
        return $data;
    }
}
