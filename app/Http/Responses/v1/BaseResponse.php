<?php declare(strict_types = 1);

namespace App\Http\Responses\v1;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;

/**
 * Abstract class for all Responses to implement
 */
abstract class BaseResponse extends Response
{
    /**
     * Data required to build the response.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Response status code.
     *
     * @var integer
     */
    protected $statusCode;

    /**
     * The required response specification validation rules.
     *
     * @return array
     */
    abstract public static function requiredSpec(): array;

    /**
     * The response specification validation rules.
     * Should include `this->requiredSpec()`.
     *
     * @param array|null $validatedRequest Validated request values from the user
     *
     * @return array
     */
    abstract public static function responseSpec($validatedRequest): array;

    /**
     * Format internal data into required API specification.
     *
     * @param mixed $data Internal data that requires formatting
     * @param array|null $validatedRequest Validated request values from the user
     *
     * @return mixed
     */
    abstract public function formatData($data, $validatedRequest);

    /**
     * Constructor
     *
     * @param int $statusCode
     */
    final public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
        parent::__construct('', $statusCode);
    }

    /**
     * Generate the response
     *
     * @param mixed $inputData
     * @param mixed $validatedRequest
     *
     * @return Response
     */
    final public function generateResponse($inputData, $validatedRequest)
    {
        $this->data = $inputData;
        $responseData = $this->getResponseData($inputData, $validatedRequest);
        $this->setContent($responseData);
        $this->setStatusCode($this->statusCode);

        return $this;
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get validated and filtered data according
     * to the response spec
     *
     * @param array $inputData
     * @param array $validatedRequest
     *
     * @return mixed
     */
    public function getResponseData($inputData, $validatedRequest)
    {
        $this->data = $inputData;
        $formattedResponse = $this->formatData($inputData, $validatedRequest);

        // Skip validation for html
        if ('text/html' == $this->getContentType()) {
            return $formattedResponse;
        }

        return ValidationHelper::filterValidateData($formattedResponse, static::responseSpec($validatedRequest));
    }

    /**
     * Get the content type of the response
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }
}
