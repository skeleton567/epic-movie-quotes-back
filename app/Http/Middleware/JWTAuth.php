<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;

class JwtAuth
{
    public function handle(Request $request, Closure $next): JsonResponse | Closure
    {
        try {
            if (request()->cookie('access_token')) {
                $token = request()->cookie('access_token');
            }

            if (!isset($token)) {
                return response()->json(["message" => "token not present"], 401);
            }

            JWT::decode(
                $token,
                new Key(
                    config('auth.jwt_secret'),
                    'HS256'
                )
            );

            return $next($request);
        } catch (Exception $e) {
            return response()->json(["message" => "token is invalid or expired "], 401);
        }
    }
}
