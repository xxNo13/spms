<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeadNotPresident
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
        foreach (auth()->user()->offices as $office) {
            if ($office->pivot->isHead && !(str_contains(strtolower($office->office_name), 'college president'))) {
                return $next($request);
            }
        }
        abort(403);
    }
}
