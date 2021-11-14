<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Cookie;

class UserController extends Controller
{
    // Create a user
    public function create_user(Request $request)
    {
        // validate arguments
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|alpha'
        ]);

        // create user
        try {
            if (User::create([
                'name' => request('name'),
                'username' => request('username'),
                'password' => Hash::make(request('password'))
            ])) {
                return response()->json(['status' => 'User created successfuly!'], 201);
            }
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error creating user'], 500);
        }
    }


    // Authenticate a user
    public function authenticate(Request $request)
    {
        // validate arguments
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // get user
        $user = User::where('username', request('username'));
        if (!$user) {
            return response()->json(['error' => 'User not found'], 401);
        }

        // check password matches. If it does, generate an api key
        try {
            if(Hash::check(request('password'), $user->first()->password)){

                // set cookie
                $apikey = Str::random(40);
                $session_id = base64_encode(request('username').":".$apikey);
                $session_duration  = env('SESSION_DURATION', 30);

                $user->update(['api_key' => $apikey]);
                $cookie = new Cookie('login_cookie', $session_id, strtotime("+$session_duration minute"));
                return response()->json(['status' => 'Login Successful'], 201)->cookie($cookie);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating user'], 500);
        }
    }
}
