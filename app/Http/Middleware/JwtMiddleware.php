<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Route;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'payload' => null,
                    'message' => 'Token is invalid',
                    'status'  => 'false',
                    'status_code'  => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                /*if (Route::getRoutes()->match($request)->getName() === 'refresh.token') {
                    return $next($request);
                }*/
                return response()->json([
                    'payload' => null,
                    'message' => 'User session is expired',
                    'status'  => 'false',
                    'status_code'  => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json([
                    'payload' => null,
                    'message' => 'Authorization token not found',
                    'status'  => 'false',
                    'status_code'  => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        return $next($request);
    }
}
