<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BuAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('business')->check()) {
            $user = Auth::guard('business')->user();
            if($user->role->role_name === 'admin'){
                return $next($request);
            }
            Auth::guard('business')->logout();
        }
        return redirect()->route('buLogin');
    }
}
