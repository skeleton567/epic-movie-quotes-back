<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifySecondaryEmail;
use Illuminate\Contracts\Notifications\Dispatcher;

class SecondaryEmail extends Model implements MustVerifyEmail
{
    use HasFactory;
    protected $guarded =['id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifySecondaryEmail());
    }
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
    public function getEmailForVerification()
    {
        return $this->email;
    }
    public function notify($instance)
    {
        app(Dispatcher::class)->send($this, $instance);
    }
    public function routeNotificationFor()
    {
        return $this->email;
    }
}
