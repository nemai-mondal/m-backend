<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SwaggerAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $swaggerAccessCode = env('SWAGGER_ACCESS_CODE');

        if (!$swaggerAccessCode || $request->accessCode !== $swaggerAccessCode) {
            return response()->json(["message" => "Unathenticated."], 401);
        }

        return $next($request);
    }
}
