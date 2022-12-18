<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Helpers\ControllerHelper;
use App\Helpers\JWTHelper;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request TODO
     * @param $next TODO
     * @param $guard TODO
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        $jwt = $request->bearerToken();

        if (! is_null($jwt)) {
            $decoded = JWTHelper::decode($jwt);

            if (is_null($decoded)) {
                return ControllerHelper::getErrorResponse([401])[401];
            }
            $request['jwt'] = $decoded;
        }

        return $next($request);
    }
}
