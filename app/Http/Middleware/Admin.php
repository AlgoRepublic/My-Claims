<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        if(!Auth::check()) {
            return redirect()->route('adminLogin');
        }

        $isAdmin = false;
        // Check if user does not have admin role than redirect it back to its origin
        if(Auth::user()->roles->role_name == 'admin')
            $isAdmin = true;

        if(!$isAdmin)
            return redirect()->intended();

        return $next($request);
    }
}
