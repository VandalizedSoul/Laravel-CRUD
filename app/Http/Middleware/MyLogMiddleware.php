<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MyLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $message)
    {
        echo "MyLog message: ",$message;
        return $next($request);
    }
}
