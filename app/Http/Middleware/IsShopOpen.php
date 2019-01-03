<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsShopOpen {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = NULL) {
        if (env('SHOP_CLOSED', false) AND !(Auth::check() AND Auth::user()->is_admin)) {
            return redirect('/');
        }
        
        return $next($request);
    }
}
