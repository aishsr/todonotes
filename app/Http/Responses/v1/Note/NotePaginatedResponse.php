<?php declare(strict_types = 1);

namespace App\Http\Responses\v1\Note;

use App\Http\Responses\v1\BaseResponse;
use App\Helpers\ValidationHelper;

use Illuminate\Support\Facades\Log;

class NotePaginatedResponse extends BaseResponse
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
            'data.*' => 'required|array:uuid,userid,content,completion_time,created_at,updated_at',
            'data.*.uuid' => 'string',
            'data.*.userid' => 'string',
            'data.*.content' => 'string',
            'data.*.completion_time' => 'nullable|date',
            'data.*.created_at' => 'date',
            'data.*.updated_at' => 'date',
        ];
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
