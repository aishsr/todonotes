<?php

namespace App\Http\Controllers;

use App\Models\ToDoNote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ToDoNoteController extends Controller
{
    /**
     *  Create a TODO note
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function create_todonote(Request $request)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        try {
            $ToDoNote = ToDoNote::create([
                'userid' => $request->auth,
                'content' => request('content')
            ]);
            return response()->json([
                'uuid' => $ToDoNote->uuid,
                'status' => 'Todo note created successfully!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching entries for user'], 500);
        }
    }

    /**
     *  List all TODO notes for logged in user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function showAllToDoNotesForCurrentUser(Request $request)
    {
        $userid = $request->auth;

        if (!$userid) {
            return response()->json(['error' => 'Unauthorized'], 500);
        }
        try {
            return response()->json([
                'status' => '200',
                'message' => 'All notes for the current user',
                'notes' => ToDoNote::where('userid', $userid)->select('uuid','completion_time','content')->get()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching entries for user'], 405);
        }
    }

     /**
     *  List all TODO notes for arbitrary user
     *
     * @param  mixed  $userid
     * @return mixed
     */
    public function showAllToDoNotesForGivenUser($userid)
    {
        if (!$userid) {
            return response()->json(['error' => 'Unauthorized'], 500);
        }
        try {
            return response()->json([
                'status' => '200',
                'message' => 'All notes for this user',
                'notes' => ToDoNote::where('userid', $userid)->select('uuid','completion_time','content')->get()],
                200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching entries for user'], 405);
        }
    }

     /**
     *  Mark TODO Note as complete
     *
     * @param  mixed  $id
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update_complete($id, Request $request)
    {
        // check todo note ID is present
        if (!$id) {
            return response()->json(['error' => 'Please enter valid ID'], 404);
        }

        // check user is authenticated and the todo note they want to edit
        try {
            $ToDoNote = ToDoNote::findOrFail($id);
            if ($ToDoNote->userid != $request->auth) {
                return response()->json(['error' => 'Unauthorized'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'To-Do Note not found'], 404);
        }

        // update todo note's completion time
        try {
            if ($ToDoNote->update(array('completion_time' => Carbon::now()))) {
                return response()->json(['status' => 'To do note marked as complete'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'To-Do Note not found'], 404);
        }
    }

     /**
     *  Mark TODO Note as incomplete
     *
     * @param  mixed  $id
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update_incomplete($id, Request $request)
    {
        // check todo note ID is present
        if (!$id) {
            return response()->json(['error' => 'Please enter valid ID'], 404);
        }

        // check user is authenticated and the todo note they want to edit
        try {
            $ToDoNote = ToDoNote::findOrFail($id);
            if ($ToDoNote->userid != $request->auth) {
                return response()->json(['error' => 'Unauthorized'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'To-Do Note not found'], 404);
        }

        // update todo note's completion time
        try {
            if ($ToDoNote->update(array('completion_time' => null))) {
                return response()->json(['status' => 'To do note marked as incomplete'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'To-Do Note not found'], 404);
        }
    }

     /**
     *  Delete a TODO Note
     *
     * @param  mixed  $id
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function delete(Request $request, $id)
    {

        // check todo note ID is present
        if (!$id) {
            return response()->json(['error' => 'Please enter valid ID'], 404);
        }

        // check if todonote exists
        try {
            $ToDoNote = ToDoNote::findOrFail($id);
            if ($ToDoNote->userid != $request->auth) {
                return response()->json(['error' => 'Unauthorized'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Todo note not found'], 404);
        }

        // delete todonote
        try {
            if ($ToDoNote->delete()) {
                return response()->json(['status' => 'To-Do Note Deleted Successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'Todo note not found'], 404);
        }
    }
}
