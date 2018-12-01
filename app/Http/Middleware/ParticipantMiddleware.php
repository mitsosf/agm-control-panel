<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ParticipantMiddleware
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

            if ($role == 1) {
                return $next($request);
            }
        }
        return redirect(route('home'));
    }
}
