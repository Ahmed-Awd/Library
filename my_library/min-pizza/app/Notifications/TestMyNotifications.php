<?php

namespace App\Notifications;

use App\Channels\OneSignalCustomerChannel;
use App\Channels\OneSignalRestaurantChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class TestMyNotifications extends Notification
{
    use Queueable;


    public function __construct()
    {
        //
    }


    public function via($notifiable)
    {
        return [OneSignalRestaurantChannel::class, OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $type = "test one";
        $url = "url";
        $message =  OneSignalMessage::create()
            ->setSubject("test admin panel")
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
            ->setData('type', $type)
            ->setData('url', $url);

        if ($notifiable->hasRole('super_admin') || $notifiable->hasRole('admin')) {
            $message = $message->setUrl(config("app.admin_url")."orderDetails/5")
                ->webButton(
                    OneSignalWebButton::create('link-1')
                    ->text('show')
                    ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
                    ->url(config("app.admin_url")."orderDetails/5")
                );//yea
        }
        return $message;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
