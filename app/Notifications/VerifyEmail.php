<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
    public function toMail($notifiable): MailMessage
    {
        $notifiable->secondary_email = $notifiable->email;
        $url = $this->verificationUrl($notifiable);
        return (new MailMessage())
            ->subject('Verify Email')
            ->view(
                'email.verify-message',
                ['url' => $url,
                'name' => $notifiable->name]
            );
    }
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }
}
