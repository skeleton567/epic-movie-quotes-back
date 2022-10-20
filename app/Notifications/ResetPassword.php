<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

class ResetPassword extends ResetPasswordBase
{
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);
        return (new MailMessage())
        ->subject(Lang::get(__('email.subject_reset')))
        ->view(
            'email.reset-message',
            ['url' => $url,
            'name' => $notifiable->name]
        );
    }
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }
}
