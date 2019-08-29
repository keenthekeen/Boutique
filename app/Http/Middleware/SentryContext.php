<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Sentry\State\Scope;

class SentryContext {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (app()->bound('sentry')) {
            /** @var User $user */
            $user = $request->user();
            \Sentry\configureScope(function (Scope $scope) use ($user, $request): void {
                if ($user) {
                    $scope->setUser([
                        'id' => $user->id,
                        'username' => $user->name,
                        'email' => $user->email,
                        'ip_address' => $request->ip()
                    ]);
                } else {
                    $scope->setUser([
                        'ip_address' => $request->ip()
                    ]);
                }
            });
        }
        
        return $next($request);
    }
}
