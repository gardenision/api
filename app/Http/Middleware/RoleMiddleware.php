<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $check = $request->user()->role()->withWhereHas('role', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->count();

        if (! $check) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}
