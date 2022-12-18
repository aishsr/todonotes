<?php declare(strict_types = 1);

namespace App\Http\Responses\v1;

use App\Http\Responses\v1\BaseResponse;
use Exception;

/**
 * Base Pagination Response
 * Expects data to be passed as a collection
 * of paginated data from a Single Response.
 */
class PaginatedResponse extends BaseResponse
{
    /**
     * The collectable response class,
     * should be set by child class
     *
     * @return string
     */
    private $_collectableResponseClass;

    /**
     * Set the collectable response class
     *
     * @param string $_collectableResponseClass
     *
     * @return void
     */
    final public function setCollectableResponseClass($_collectableResponseClass)
    {
        $this->_collectableResponseClass = $_collectableResponseClass;
    }

    /**
     * Response spec
     *
     * @return array
     */
    public static function requiredSpec(): array
    {
        return [
            'data' => 'required|array',
            'pagination' => 'required|array',
            'pagination.current_page' => 'required|integer',
            'pagination.first_page_url' => 'required|url',
            'pagination.from' => 'required|integer',
            'pagination.last_page' => 'required|integer',
            'pagination.last_page_url' => 'required|url',
            'pagination.next_page_url' => 'required|nullable|url',
            'pagination.path' => 'required|string',
            'pagination.perPage' => 'required|integer',
            'pagination.prev_page_url' => 'required|nullable|url',
            'pagination.to' => 'required|integer',
            'pagination.total' => 'required|integer',
        ];
    }

    /**
     * Response spec
     *
     * @param mixed $validatedRequest
     *
     * @return array
     */
    public static function responseSpec($validatedRequest): array
    {
        return static::requiredSpec();
    }

    /**
     * Format the data
     *
     * @param mixed $data
     * @param mixed $validatedRequest
     *
     * @return array
     */
    public function formatData($data, $validatedRequest)
    {
        if (! is_a($data, '\Illuminate\Pagination\LengthAwarePaginator')) {
            throw new Exception('Paginated response expects LengthAwarePaginator as data argument.');
        }

        $formattedData = [];
        $collection = $data->getCollection();
        $statusCode = $this->getStatusCode();

        foreach ($collection as $item) {
            $formattedData[] = (new $this->_collectableResponseClass($statusCode))->getResponseData($item, $validatedRequest);
        }

        return [
            'data' => $formattedData,
            'pagination' => static::formatPaginationData($data),
        ];
    }

    /**
     * Format the pagination data
     *
     * @param mixed $data
     *
     * @return array
     */
    public static function formatPaginationData($data)
    {
        // TODO(ryan): fix this to use actual full URLs, with the same query parameters (and not hardcoded/any domain)
        $firstPage = 1;
        $lastPage = $data->lastPage();
        $currentPage = $data->currentPage();

        return [
            'current_page' => $currentPage,
            'first_page_url' => 'https://127.0.0.1:9909' . $data->url($firstPage),
            'from' => $data->firstItem(),
            'last_page' => $lastPage,
            'last_page_url' => 'https://127.0.0.1:9909' . $data->url($lastPage),
            'next_page_url' => 'https://127.0.0.1:9909' . $data->nextPageUrl(),
            'path' => $data->url($currentPage),
            'perPage' => $data->perPage(),
            'prev_page_url' => 'https://127.0.0.1:9909' . $data->previousPageUrl(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
        ];
    }

    /**
     * Create a new instance
     *
     * @param string $subResponseClass
     * @param int $statusCode
     *
     * @return self
     */
    public static function createInstance($subResponseClass, $statusCode)
    {
        $resp = new self($statusCode);
        $resp->setCollectableResponseClass($subResponseClass);

        return $resp;
    }
}
