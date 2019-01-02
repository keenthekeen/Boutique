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
            $request->session()->flash('message', 'คุณต้องเข้าสู่ระบบก่อน');
            $request->session()->flash('message_text_color', 'white');
            $request->session()->flash('message_box_color', 'red');

            if ($request->is('merchant/register')){
                $request->session()->put('is_merchant', true);
            }

            return redirect('/');
        }
        
        return $next($request);
    }
}
