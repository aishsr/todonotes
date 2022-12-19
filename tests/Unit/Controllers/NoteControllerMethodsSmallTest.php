<?php declare(strict_types = 1);

namespace Tests\Unit\Controllers;

use App\Helpers\TestHelper;
use App\Http\Controllers\v1\BaseController;
use App\Http\Controllers\v1\NoteController;
use App\Http\Requests\v1\Note\StoreRequest;
use App\Http\Requests\v1\Note\UpdateRequest;
use App\Http\Responses\v1\Error\MessageResponse;
use App\Http\Responses\v1\Note\NotePaginatedResponse;
use App\Http\Responses\v1\Note\NoteResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\Unit\UnitTestCase;

/**
 * Test the NoteController class methods.
 *
 * @covers App\Http\Controllers\v1\NoteController
 *
 * @large
 *
 * @internal
 */
class NoteControllerMethodsSmallTest extends UnitTestCase
{
    /**
     * The class being tested
     *
     * @var NoteController
     */
    public static NoteController $controllerClass;

    /**
     * Setup before class is loaded.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$controllerClass = new NoteController();
    }

    /**
     * @testdox Test the get request method has correctly mapped request classes
     *
     * @return void
     */
    public function testGetRequest(): void
    {
        $assertMessage = 'Request class is of incorrect instance';

        $this->assertTrue(
            static::$controllerClass::getRequest('store') instanceof StoreRequest,
            $assertMessage
        );
        $this->assertTrue(
            static::$controllerClass::getRequest('update') instanceof UpdateRequest,
            $assertMessage
        );

        $this->expectException('InvalidArgumentException');
        static::$controllerClass::getRequest('incorrect_method');
    }

    /**
     * @testdox Test the get response method has correctly mapped response classes
     *
     * @return void
     */
    public function testGetResponse(): void
    {
        $assertMessage = 'Response class is of incorrect instance';

        $this->assertTrue(
            static::$controllerClass::getResponse('index') instanceof NotePaginatedResponse,
            $assertMessage
        );
        $this->assertTrue(
            static::$controllerClass::getResponse('show') instanceof NoteResponse,
            $assertMessage
        );
        $this->assertTrue(
            static::$controllerClass::getResponse('store') instanceof NoteResponse,
            $assertMessage
        );
        $this->assertTrue(
            static::$controllerClass::getResponse('delete') instanceof MessageResponse,
            $assertMessage
        );

        $this->expectException('InvalidArgumentException');
        static::$controllerClass::getResponse('incorrect_method');
    }

    /**
     * @testdox Test get error response returns error for unknown method
     *
     * @return void
     */
    public function testUnknownMethodErrorResponse(): void
    {
        $this->expectException('InvalidArgumentException');
        static::$controllerClass::getErrorResponse('incorrect_method');
    }

    /**
     * @testdox Test that validation error response is returned for those methods requiring requests
     *
     * @return void
     */
    public function testSuccessResponse(): void
    {
        // pick random note ID for testing
        $noteId = DB::table('notes')->get()->random()->uuid;

        // index
        $request = TestHelper::generateRequestFromArray([]);
        $response = self::$controllerClass->index($request);
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code received for index');

        // show
        $request = TestHelper::generateRequestFromArray([]);
        $response = self::$controllerClass->show($request, $noteId);
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code received for show');

        // store
        $request = TestHelper::generateRequestFromArray(['content' => 'Content for testing']);
        $response = self::$controllerClass->store($request);
        $this->assertEquals(201, $response->getStatusCode(), 'Incorrect status code received for store');

        // update - mark note as complete
        $request = TestHelper::generateRequestFromArray(['makeComplete' => 'true']);
        $response = self::$controllerClass->update($request, $noteId);
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code received for updating note as complete');

        // update - mark note as incomplete
        $request = TestHelper::generateRequestFromArray(['makeComplete' => 'false']);
        $response = self::$controllerClass->update($request, $noteId);
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code received for updating note as incomplete');

        // update - mark note as incomplete
        $request = TestHelper::generateRequestFromArray([]);
        $response = self::$controllerClass->delete($request, $noteId);
        $this->assertEquals(200, $response->getStatusCode(), 'Incorrect status code received for delete');
    }

    /**
     * @testdox Test that validation error response is returned for those methods requiring requests
     *
     * @return void
     */
    public function testValidationErrorResponseIsReturned(): void
    {
        // pick random note ID for testing
        $noteId = DB::table('notes')->get()->random()->uuid;

        // store
        $request = TestHelper::generateRequestFromArray(['content' => null]);
        $response = self::$controllerClass->store($request);
        $this->assertEquals(422, $response->getStatusCode(), 'Incorrect status code received for store when content is null');

        $request = TestHelper::generateRequestFromArray(['content' => 12345]);
        $response = self::$controllerClass->store($request);
        $this->assertEquals(422, $response->getStatusCode(), 'Incorrect status code received for store when content is numerical');

        // update
        $request = TestHelper::generateRequestFromArray(['incorrect_field' => 'true']);
        $response = self::$controllerClass->update($request, $noteId);
        $this->assertEquals(422, $response->getStatusCode(), 'Incorrect status code received for update');
    }

    /**
     * @testdox Test that item not found/error exception is returned if non existent ID is given
     *
     * @return void
     */
    public function testItemNotFoundExceptionIsReturned(): void
    {
        // Non existent ID for testing
        $noteId = 'ffffffff-0000-0000-ffff-000000000001';

        // show
        $request = TestHelper::generateRequestFromArray([]);
        $response = self::$controllerClass->show($request, $noteId);
        $this->assertEquals(404, $response->getStatusCode(), 'Incorrect status code received for show');

        // update
        $request = TestHelper::generateRequestFromArray(['makeComplete' => 'false']);
        $response = self::$controllerClass->update($request, $noteId);
        $this->assertEquals(500, $response->getStatusCode(), 'Incorrect status code received for update');

        // delete
        $request = TestHelper::generateRequestFromArray([]);
        $response = self::$controllerClass->delete($request, $noteId);
        $this->assertEquals(404, $response->getStatusCode(), 'Incorrect status code received for delete');
    }
}
