<?php declare(strict_types = 1);

namespace App\Http\Requests\v1\Note;

use App\Http\Requests\v1\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Returns the validation rules for the request
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'makeComplete' => 'required',
        ];
    }
}
