<?php

namespace App\Providers;

use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $frontendUrl = env('BASE_URL'). '/success';

            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return $frontendUrl . '?verify_url=' . urlencode($verifyUrl);
        });
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            $email =  $notifiable->getEmailForPasswordReset();
            return  env('BASE_URL') . "/create-password?token=$token&email=$email";
        });
    }
}
