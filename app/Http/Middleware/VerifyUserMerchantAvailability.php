<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserMerchantAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(Auth::check() && $request->user()->merchants && !$request->session()->get('merchant')){
            $request->session()->put('merchant', $request->user()->merchants?->first()?->id);
            return to_route('dashboard');
        }

        return $next($request);
    }
}
