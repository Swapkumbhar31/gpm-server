<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminAuth
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
        if (Auth::user() === null) {
            return redirect('/login');
        }
        if (Auth::user()->isAdmin) {
            return $next($request);
        } else {
            return response('The request has not been applied because it lacks valid authentication credentials for the target resource.', 401);
        }
    }
}
