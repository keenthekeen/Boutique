<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Admin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::check()) {
            return redirect()->guest('/login');
        } elseif (empty(Auth::user()->is_admin)) {
            return response()->view('errors.403', [], 403);
        }
        
        return $next($request);
    }
}
