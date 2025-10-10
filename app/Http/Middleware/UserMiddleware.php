<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //jika user
        if (auth()->guest() || auth()->user()->role_id !== 3) {
            abort(403);
        }
        return $next($request);
    }
}
