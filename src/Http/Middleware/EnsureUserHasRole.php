<?php

namespace Tuna976\NEWS\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        if (($role === 'admin' && !$request->user()->isAdmin()) || 
            ($role === 'author' && !$request->user()->isAuthor())) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
