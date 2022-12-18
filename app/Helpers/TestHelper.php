<?php declare(strict_types = 1);

namespace App\Helpers;

class TestHelper
{
    /**
     * Generate a request object from array
     *
     * @param array $requestValues
     *
     * @return \Illuminate\Http\Request
     */
    public static function generateRequestFromArray(array $requestValues = [])
    {
        return new \Illuminate\Http\Request($requestValues);
    }
}
