<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckinMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (isset(Auth::user()->role_id)) {
            $role = Auth::user()->role_id;

            if ($role == 3 || $role == 2) {
                return $next($request);
            }
        }
        return redirect(route('home'));
    }
}
