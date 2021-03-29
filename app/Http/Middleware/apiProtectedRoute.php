<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};

class apiProtectedRoute extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $exception) {
            
            if ($exception instanceof TokenInvalidException){
                return response()->json(['status' => 'Token inválido'], 401);
            } else if ($exception instanceof TokenExpiredException){
                return response()->json(['status' => 'Token expirado.'], 401);
            } else {
                return response()->json(['status' => 'Autorização do Token não encontrada.'], 401 );
            }

        }
        return $next($request);
    }
}
