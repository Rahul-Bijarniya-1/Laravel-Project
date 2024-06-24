<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('dummy_authorization_token');

        if (!$token || $token !== 'dummy') {
            return response()->json(['error' => 'dummy_authorization_token not present'], 512);
        }
        return $next($request);
    }
}
