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

        return $next($request);
    }
}
