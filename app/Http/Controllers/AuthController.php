<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        $token = auth()->login($user);
        return $this->respondWithToken($token);
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

        if ($request->has('remember')) {
            $ttl = '1051200';
        } else {
            $ttl = auth()->factory()->getTTL() * 60;
        }

        return $this->respondWithToken($token, $ttl);
    }

    public function googleLogin(GoogleLoginRequest $request): JsonResponse
    {
        if (User::where('email', $request->email)->exists()) {
            $user = User::firstWhere('email', $request->email);
        } else {
            $user = User::create($request->validated());
            $user->markEmailAsVerified();
        }
        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function verify(Request $request): JsonResponse
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Successfully verified'], 200);
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
    protected function respondWithToken(string $token, string  $ttl = null): JsonResponse
    {
        if (!$ttl) {
            $ttl = auth()->factory()->getTTL() * 60;
        }
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
        ]);
    }
}
