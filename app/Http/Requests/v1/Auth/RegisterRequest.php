<?php declare(strict_types = 1);

namespace App\Http\Requests\v1\Auth;

use App\Http\Requests\v1\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Returns the validation rules for the request
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string|max:5000',
        ];
    }
}
