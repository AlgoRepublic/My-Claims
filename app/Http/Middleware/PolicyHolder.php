<?php

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;

class PolicyHolder
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
            return redirect()->route('policyLogin');
        }

        $isPolicyHolder = false;
        // Check if user does not have admin role than redirect it back to its origin
        if(Auth::user()->roles->role_name == 'policyholder')
            $isPolicyHolder = true;

        if(!$isPolicyHolder) {
            // This means that admin is logged in, so redirect it back to policy holder login and log it out as well
            Auth::logout();
            //return redirect()->intended();
            return redirect('/policyHolder/login');
        }

        return $next($request);
    }
}
