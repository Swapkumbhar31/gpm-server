<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;
class CheckAPI
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
        $api = isset($request->api_key) ? $request->api_key : Auth::user()->api_key;
        $user = User::where('api_key', $request->api_key)->first();
        if ($user === null) {
            $result['msg'] = 'Forbidden.';
            return response(json_encode($result), 403);
        }
        $request->request->add(['user_id'=> $user->id]);
        return $next($request);
    }
}
