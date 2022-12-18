<?php declare(strict_types = 1);

namespace App\Exceptions;

use Exception;
use Throwable;

class ScrawlrException extends Exception
{
    public $responseStatusCode;

    /**
     * Constructor
     *
     * @param mixed $message
     * @param mixed $code
     * @param mixed $responseStatusCode
     */
    public function __construct($message, $code, $responseStatusCode, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->responseStatusCode = $responseStatusCode;
    }

    public function getResponseStatusCode()
    {
        return $this->responseStatusCode;
    }
}
