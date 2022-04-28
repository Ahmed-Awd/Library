<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{

    public static $createUrlCallback;


    public static $toMailCallback;


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return $this->buildMailMessage($verificationUrl, $notifiable);
    }


    protected function buildMailMessage($url, $notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('messages.auth.verification_mail'))
            ->view('emails.verification_mail', ['url' => $url,'user' =>$notifiable]);
//            ->action(Lang::get('Verify Email Address'), $url);
    }


    protected function verificationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addYear(1),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }


    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }


    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
