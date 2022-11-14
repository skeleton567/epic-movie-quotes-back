<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifySecondaryEmail extends VerifyEmailBase
{
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);
        $name = User::find($notifiable->user_id)->name;
        return (new MailMessage())
            ->subject('Verify Email')
            ->view(
                'email.verify-message',
                ['url' => $url,
                'name' =>  $name]
            );
    }
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }
}
