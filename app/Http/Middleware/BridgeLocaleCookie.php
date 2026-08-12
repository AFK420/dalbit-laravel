<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BridgeLocaleCookie
{
    /**
     * Hydrates the session locale from the permanent cookie on visits
     * where the session doesn't already carry a locale (e.g. a new
     * browser session for a returning visitor).
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('locale') && $request->cookie('locale')) {
            $request->session()->put('locale', $request->cookie('locale'));
        }

        return $next($request);
    }
}
