<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Rediriger selon le rôle
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('professeur.dashboard');
            }
        }

        return $next($request);
    }
}