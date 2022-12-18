<?php

declare(strict_types = 1);

namespace App\Http\Controllers\v1;

use App\Helpers\ControllerHelper;
use App\Http\Responses\v1\BaseResponse;
use App\Services\NoteService;
use App\Http\Requests\v1\BaseRequest;
use App\Http\Requests\v1\Note\IndexRequest;
use App\Http\Requests\v1\Note\ShowRequest;
use App\Http\Requests\v1\Note\StoreRequest;
use App\Http\Requests\v1\Note\UpdateRequest;
use App\Http\Responses\v1\Error\MessageResponse;
use App\Http\Responses\v1\Note\NotePaginatedResponse;
use App\Http\Responses\v1\Note\NoteResponse;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Exception;

/**
 * Controller for To Do Notes
 */
class NoteController extends BaseController
{
    protected NoteService $service;

    /**
     * Return the BaseRequest or null associated with controller method.
     *
     * @param string $method The controller method being called.
     *
     * @throws \InvalidArgumentException
     *
     * @return BaseRequest|null
     */
    public static function getRequest($method)
    {
        switch ($method) {
            case 'store':
                return new StoreRequest();
            case 'update':
                return new UpdateRequest();
            case 'show':
            case 'index':
            case 'delete':
                return null;
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

    /**
     * Return BaseResponse associated with a method.
     *
     * @param string $method The controller method being called.
     *
     * @throws \InvalidArgumentException
     *
     * @return BaseResponse
     */
    public static function getResponse($method)
    {
        switch ($method) {
            case 'index':
                return new NotePaginatedResponse(200);
            case 'store':
                return new NoteResponse(201);
            case 'show':
            case 'update':
                return new NoteResponse(200);
            case 'delete':
                return new MessageResponse(200);
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

    /**
     * Return an array of error responses.
     * Where the key is the status code and value is a BaseResponse.
     *
     * @param string $method The controller method being called
     *
     * @throws \InvalidArgumentException
     *
     * @return array<int=>BaseResponse>
     */
    public static function getErrorResponse($method): array
    {
        switch ($method) {
            case 'index':
                return ControllerHelper::getErrorResponse([401, 500]);
            case 'store':
            case 'show':
            case 'update':
            case 'delete':
                return ControllerHelper::getErrorResponse([400, 401, 404, 500]);
            default:
                static::invalidMethod($method, __FUNCTION__);
        }
    }

    /**
     * Define the services used by the this controller.
     *
     * @return void
     */
    protected function defineServices(): void
    {
        $this->service = new NoteService();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return BaseResponse
     */
    public function index(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);

            $data = $this->service->index();

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__, $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return BaseResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);

            $data = $this->service->store($validated);

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__, $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param string $id
     *
     * @return BaseResponse
     */
    public function show(Request $request, string $id)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);

            $data = $this->service->show($id);

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__, $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     *
     * @return BaseResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);

            $data = $this->service->update($validated, $id);

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__, $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $id
     *
     * @return BaseResponse
     */
    public function delete(Request $request, $id)
    {
        try {
            $validated = $this->validateRequest($request, __FUNCTION__);

            $data = $this->service->delete($id);

            return static::getResponse(__FUNCTION__)->generateResponse($data, $validated);
        } catch (Exception $e) {
            return ControllerHelper::formatExceptionResponse($this, $e, __FUNCTION__, $e->getCode());
        }
    }
}
