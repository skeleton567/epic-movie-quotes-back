<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());

        return response()->json('User successfuly registered!', 200);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (filter_var($credentials['name'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $credentials['name'];
            unset($credentials['name']);
        }
        $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Name or password is not correct'], 404);
        }

        return $this->respondWithToken($token);
    }

    public function user(): JsonResponse
    {
        return response()->json(auth()->user(), 200);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}
