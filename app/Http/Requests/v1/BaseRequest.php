<?php declare(strict_types = 1);

namespace App\Http\Requests\v1;

/**
 * Abstract class for all Requests to implement
 */
abstract class BaseRequest
{
    /**
     * Returns the validation rules for the request
     *
     * @return array
     */
    abstract public static function rules() : array;
}
