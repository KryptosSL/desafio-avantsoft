<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckTokenValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = PersonalAccessToken::findToken($request->bearerToken());
 
        if (!$token) {
            return response()->json(['message' => 'Token inválido ou expirado'], 401);
        }

        return $next($request);
    }
}
