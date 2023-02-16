<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Faculty
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
        foreach (auth()->user()->account_types as $account_type) {
            if (str_contains(strtolower($account_type->account_type), 'faculty')) {
                return $next($request);
            }
        }
        abort(403);
    }
}
