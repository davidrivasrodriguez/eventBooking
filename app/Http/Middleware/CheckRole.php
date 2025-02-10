<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || !$request->user()->role) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->user()->role->name === $role) {
            return $next($request);
        }

        abort(403);
    }
}