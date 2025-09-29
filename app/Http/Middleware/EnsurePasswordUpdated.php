<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordUpdated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && is_null($user->email_verified_at) && !$request->routeIs('password.forceUpdate', 'password.forceUpdate.store', 'logout')) {
            return redirect()->route('password.forceUpdate');
        }

        return $next($request);
    }
}
