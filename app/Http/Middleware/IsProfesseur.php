<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsProfesseur
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();
    if (!$user || $user->role !== 'professeur' || !$user->isValidated()) {
        abort(403, 'Compte non validé ou accès interdit');
    }
    return $next($request);
}
}
