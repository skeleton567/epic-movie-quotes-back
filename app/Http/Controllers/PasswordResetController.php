<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordReset\ForgotPasswordRequest;
use App\Http\Requests\PasswordReset\UpdatePasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' =>'Email sent!'], 200)
                    : response()->json(['error' => __($status)], 404);
    }

    public function passwordUpdate(UpdatePasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['message' => 'Password updated!'], 200)
                    : response()->json(['error' => __($status)], 404);
    }
}
