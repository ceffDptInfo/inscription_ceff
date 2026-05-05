<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCandidat
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('candidat_id')) {
            return $next($request);
        } else {
            return redirect('/candidat-login')->withErrors(['email' => 'Utilisateur non indentifié']);
        }
    }
}
