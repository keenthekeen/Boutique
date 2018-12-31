<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Gate;

class MyAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::check()) {
            $request->session()->put('message', 'คุณต้องเข้าสู่ระบบก่อน');
            $request->session()->put('message_text_color', 'white');
            $request->session()->put('message_box_color', 'red');
            return redirect('/');
        }
        
        return $next($request);
    }
}
