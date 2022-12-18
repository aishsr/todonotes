<?php declare(strict_types = 1);

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\ItemNotFoundException;
use Ramsey\Uuid\Uuid;

class NoteService extends BaseService
{
    /**
     * Return a list of notes
     *     *
     * @return array
     */
    public function index(): array
    {
        $dbResults = DB::table('notes')->get()->all();
        $data = array_map(function ($item) {
            return (array)$item;
        }, $dbResults);

        return ['data' => $data];
    }

    /**
     * Create a new note
     *
     * @param array $validated Validated request
     *
     * @return array
     */
    public function store(array $validated): array
    {
        $id = Uuid::uuid4()->toString();
        $userId = DB::table('users')->get()->random()->uuid;
        $content = $validated['content'];

        try {
            DB::table('notes')->insert([
                'uuid' => $id,
                'userid' => $userId,
                'content' =>$content,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return $this->show($id);
        } catch (Exception $e) {
            throw new Exception('Error in creating note', 500);
        }
    }

    /**
     * Find a note
     *
     * @param string $id ID of the note to retrieve
     *
     * @return array
     */
    public function show(string $id): array
    {
        try {
            $dbResult = DB::table('notes')->where('notes.uuid', '=', $id)->get()->firstOrFail();

            return ['data' =>(array)$dbResult];
        } catch (ItemNotFoundException $e) {
            throw new Exception('The provided ID does not exist', 404);
        }
    }

    /**
     * Update a note as completed
     *
     * @param array $validated Validated request
     * @param string $id ID of the note to update
     *
     * @return array
     */
    public function update(array $validated, string $id): array
    {
        if ('true' == $validated['makeComplete']) {
            $timestamp = Carbon::now();
        } elseif ('false' == $validated['makeComplete']) {
            $timestamp = null;
        } else {
            throw new Exception('Incorrect parameter given', 500);
        }

        try {
            DB::table('notes')->where('notes.uuid', '=', $id)->get()->firstOrFail();
            DB::table('notes')
                ->where('notes.uuid', '=', $id)
                ->update(['notes.completion_time' => $timestamp]);

            return $this->show($id);
        } catch (ItemNotFoundException $e) {
            throw new Exception('The provided ID does not exist', 404);
        } catch (Exception $e) {
            throw new Exception('Error in updating note', 500);
        }
    }

    /**
     * Delete a note
     *
     * @param string $id ID of the note to delete
     *
     * @return array|null
     */
    public function delete(string $id): ?array
    {
        try {
            DB::table('notes')->where('notes.uuid', '=', $id)->get()->firstOrFail();
            DB::table('notes')->where('notes.uuid', '=', $id)->delete();

            return [
                'code' => 202,
                'message' => 'Note deleted successfully',
            ];
        } catch (ItemNotFoundException $e) {
            throw new Exception('The provided ID does not exist', 404);
        } catch (Exception $e) {
            throw new Exception('Error in deleting note', 500);
        }
    }
}
