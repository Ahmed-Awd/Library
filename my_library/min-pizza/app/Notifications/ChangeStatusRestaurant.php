<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalRestaurantChannel;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Lang;

class ChangeStatusRestaurant extends Notification
{
    use Queueable;

    protected Restaurant $restaurant;


    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }


    public function via($notifiable): array
    {
        return [OneSignalRestaurantChannel::class];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toOneSignal($notifiable)
    {

        $type = "ChangeStatusRestaurant";
        $url = $this->restaurant->id;
        return OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.restaurant_status_changes',
                ['status' => $this->restaurant->status->name],
                $notifiable->default_lang
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
             ->setData('type', $type)
            ->setData('url', $url)
            ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
            ->setParameter('ios_sound', 'siren.wav')
            ->setParameter('android_sound', 'siren');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
