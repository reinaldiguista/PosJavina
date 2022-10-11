<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param mixed $level  [1. admin | 2. supervisor | 3. kasir | 4. pendamping ]
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$level)
    {
        if (auth()->user() && in_array(auth()->user()->level, $level)) {
            return $next($request);
        }

        return $next($request);
    }
}
