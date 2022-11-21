<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\SecondaryEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        $cookie = auth()->login($user);
        return response()->json('success', 200)->withCookie($cookie);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $cookie  = auth()->attempt($request->validated());

        if (!$cookie) {
            return response()->json($this->assemble_erorr(), 404);
        }

        return response()->json('success', 200)->withCookie($cookie);
    }

    public function googleLogin(GoogleLoginRequest $request): JsonResponse
    {
        $googleUser = Socialite::driver('google')->userFromToken($request->token);

        if (User::where('email', $googleUser->email)->exists()) {
            $user = User::firstWhere('email', $googleUser->email);
        } else {
            $user = User::create(['email' => $googleUser->email, 'name'=> $googleUser->name]);
            $user->google_auth = true;
            $user->markEmailAsVerified();
        }
        $cookie = auth()->login($user);
        return response()->json('success', 200)->withCookie($cookie);
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
    public function secondaryVerify(Request $request): JsonResponse
    {
        $user = SecondaryEmail::find($request->route('id'));

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
        if (!request()->cookie('access_token')) {
            throw new \ErrorException('not logged in');
        }
        return response()->json(auth()->user(), 200);
    }

    public function logout(): JsonResponse
    {
        $cookie = cookie("access_token", '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

        return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
    }

    private function assemble_erorr()
    {
        return ['errors' => [
            'ka' => __('validation.incorect_credentians', ['attribute' => 'სახელი'], 'ka'),
            'en' => __('validation.incorect_credentians', ['attribute' => 'name'], 'en')
         ]];
    }
}
