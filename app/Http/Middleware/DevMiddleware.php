<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DevMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //jika dev
        if (auth()->guest() || auth()->user()->role_id !== 1) {
            abort(403);
        }
        return $next($request);
    }
}
