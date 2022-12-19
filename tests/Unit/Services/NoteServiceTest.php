<?php declare(strict_types = 1);

namespace Tests\Unit\Services;

use App\Services\NoteService;
use Exception;
use Illuminate\Support\Facades\DB;
use Tests\Unit\UnitTestCase;

/**
 * Test the NoteService class methods.
 *
 * @covers App\Services\NoteService
 *
 * @large
 *
 * @internal
 */
class NoteServiceTest extends UnitTestCase
{
    /**
     * The class being tested
     *
     * @var NoteService
     */
    public static NoteService $serviceClass;

    /**
     * Setup before class is loaded.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$serviceClass = new NoteService();
    }

    /**
     * @testdox Test index fetches all fields
     *
     * @return void
     */
    public function testIndexFetchesResults(): void
    {
        $results = static::$serviceClass->index();
        foreach ($results['data'] as $row) {
            $this->assertTrue(array_key_exists('uuid', $row), 'Missing key uuid');
            $this->assertTrue(array_key_exists('content', $row), 'Missing key content');
            $this->assertTrue(array_key_exists('userid', $row), 'Missing key userid');
            $this->assertTrue(array_key_exists('completion_time', $row), 'Missing key completion_time');
            $this->assertTrue(array_key_exists('updated_at', $row), 'Missing key updated_at');
            $this->assertTrue(array_key_exists('created_at', $row), 'Missing key created_at');
        }
    }

    /**
     * @testdox Test show returns note that exists
     *
     * @return void
     */
    public function testShowReturnsSingularNote(): void
    {
        // pick random note
        $noteId = DB::table('notes')->get()->random()->uuid;

        $result = static::$serviceClass->show($noteId);
        $this->assertTrue(array_key_exists('uuid', $result['data']), 'Missing key uuid');
        $this->assertTrue(array_key_exists('content', $result['data']), 'Missing key content');
        $this->assertTrue(array_key_exists('userid', $result['data']), 'Missing key userid');
        $this->assertTrue(array_key_exists('completion_time', $result['data']), 'Missing key completion_time');
        $this->assertTrue(array_key_exists('updated_at', $result['data']), 'Missing key updated_at');
        $this->assertTrue(array_key_exists('created_at', $result['data']), 'Missing key created_at');
    }

    /**
     * @testdox Test show() returns error for unknown ID given
     *
     * @return void
     */
    public function testShowReturnsErrorForNonExistentId(): void
    {
        try {
            static::$serviceClass->show('ffffffff-0000-0000-ffff-000000000001');
        } catch (Exception $e) {
            $this->assertEquals('The provided ID does not exist', $e->getMessage(), 'Incorrect error message');
            $this->assertEquals(404, $e->getCode(), 'Incorrect error code');
        }
    }

    /**
     * @testdox Test note is updated as complete
     *
     * @return void
     */
    public function testNoteIsUpdatedAsComplete(): void
    {
        $result = static::$serviceClass->update(['makeComplete' => 'true'], DB::table('notes')->get()->random()->uuid);
        $this->assertNotNull($result['data']['completion_time'], 'Completion time has not been updated');
    }

    /**
     * @testdox Test note is updated as incomplete
     *
     * @return void
     */
    public function testNoteIsUpdatedAsNotComplete(): void
    {
        $result = static::$serviceClass->update(['makeComplete' => 'false'], DB::table('notes')->get()->random()->uuid);
        $this->assertNull($result['data']['completion_time'], 'Completion time has not been updated');
    }

    /**
     * @testdox Test a newly creted note is returned
     *
     * @return void
     */
    public function testCreatingNote(): void
    {
        $request = [
            'content' => 'I am creating a new note',
        ];

        $result = static::$serviceClass->store($request);
        $this->assertEquals($request['content'], $result['data']['content'], 'Incorrect note received');
    }

    /**
     * @testdox Test a deleted note can't be fetched again
     *
     * @return void
     */
    public function testDeletingNote(): void
    {
        // pick random note
        $noteId = DB::table('notes')->get()->random()->uuid;

        // delete
        $result = static::$serviceClass->delete($noteId);
        $this->assertEquals('Note deleted successfully', $result['message'], 'Incorrect success message received');

        // try fetching this note
        try {
            static::$serviceClass->show($noteId);
            $this->assertFalse(true, 'Exception failed to be thrown');
        } catch (Exception $e) {
            $this->assertEquals('The provided ID does not exist', $e->getMessage(), 'Incorrect error message');
        }
    }
}
