<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use Closure;
use App\Models\User;

/**
 * Authentication requests for users logging in
 */
class Authenticate
{
    /**
     * Handle incoming request and check user authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // extract the username and the api_key. Format -> username:api_key
        $userinfo = explode(':', base64_decode($request->cookies->get('login_cookie')));
        $username = $userinfo[0];
        $api_key = $userinfo[1];

        // get user
        if ($username && $api_key) {
            $user = User::where('api_key', $api_key)->first();

            if (! empty($user)) {
                $request->auth = $user->uuid;

                return $next($request);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['error' => 'Please login again'], 401);
    }
}
