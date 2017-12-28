<?php

namespace App\Http\Middleware;

use Closure;

class Lock
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
        if (config('app.env') == 'staging') {
            if ($request->input('passcode') == 'malicacid' OR $request->session()->get('stage_lock', false)) {
                $request->session()->put('stage_lock', true);
            } else {
                return response()->view('errors.lock');
            }
        }
        return $next($request);
    }
}
