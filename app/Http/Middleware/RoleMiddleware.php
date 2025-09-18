<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Check if the user has one of the allowed roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Session::has('role')) {
            return redirect('/login');
        }

        $userRole = Session::get('role');

        if (!in_array($userRole, $roles)) {
            // Optionally, redirect to unauthorized page or home
            return redirect('/')->withErrors(['unauthorized' => 'You do not have access to this resource.']);
        }

        return $next($request);
    }
}
