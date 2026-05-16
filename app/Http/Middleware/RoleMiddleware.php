<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {

            abort(403, 'Please login first');

        }

        if (!in_array(Auth::user()->role, $roles)) {

            abort(403, 'Access Denied');

        }

        return $next($request);
    }
}