<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the request is for an image or static file
        if ($this->isImageRequest($request)) {
            // Set CORS headers for image/static file responses
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        } else {
            // Set general CORS headers for API and other requests
            $response->headers->set('Access-Control-Allow-Origin', '*'); // Use '*' or specific domains if needed
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 204)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
