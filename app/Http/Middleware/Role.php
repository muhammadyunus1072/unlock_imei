<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, String $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if(auth()->user()->hasRole($role))
            return $next($request);
        else
            abort(404);
    }
}
