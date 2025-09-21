<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bktoken = $request->header('bktoken');
        
        // Check if the token matches
        if ($bktoken !== 'k7m3qz2zmmp9oux4ghnz10g6l90r77po8v5br4svw6pf5j5qe9fvxr6d849amvsj') {
            return response()->json(['message' => 'You are not authorized'], 403); // Use 403 Forbidden
        }

        return $next($request);
    }
}
