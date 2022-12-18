<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Responses\v1\BaseResponse;

use Illuminate\Support\Facades\Log;
use Closure;

class EnforceBaseResponseMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! ($response instanceof BaseResponse)) {
            Log::error('Wrong return class: "' . get_class($response) . '" != "' . BaseResponse::class . '"');
        }

        return $response;
    }
}
