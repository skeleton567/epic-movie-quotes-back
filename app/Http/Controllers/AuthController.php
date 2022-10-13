<?php

namespace App\Http\Controllers;

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

        if ($request->has('remember')) {
            $ttl = '1051200';
        } else {
            $ttl = auth()->factory()->getTTL() * 60;
        }

        return $this->respondWithToken($token, $ttl);
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(env('BASE_URL') . '/success');
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
        $ttl = auth()->factory()->getTTL() * 60;
        return $this->respondWithToken(auth()->refresh(), $ttl);
    }
    protected function respondWithToken(string $token, string  $ttl): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
        ]);
    }
}
