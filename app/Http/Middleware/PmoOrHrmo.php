<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PmoOrHrmo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->id == 1) {
            return $next($request);
        }
        foreach (auth()->user()->offices as $office) {
            if (str_contains(strtolower($office->office_name), 'planning')) {
                return $next($request);
            }
            if (str_contains(strtolower($office->office_name), 'hr') || str_contains(strtolower($office->office_abbr), 'hr')) {
                return $next($request);
            }
        }
        abort(403);
    }
}
