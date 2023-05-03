<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Committee
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
        $head = false;
        foreach (auth()->user()->offices as $office) {
            if ($office->pivot->isHead) {
                $head = true;
                break;
            }
        }
        if (auth()->user()->committee
         || auth()->user()->offices()->where('office_abbr', "LIKE", '%hr%')->first()
         || $head) {
            return $next($request);
        }
        abort(403);
    }
}
