<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalButton;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalStoreChannel;
use App\Channels\OneSignalDriverChannel;
use App\Models\OrderStatus;

class DriverNotArriveRestaurant extends Notification
{
    use Queueable;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database',OneSignalStoreChannel::class];
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
        $type = "DriverNotArriveRestaurant";
        $url = route('orders.show', $this->order->id);
        $order_id = $this->order->store_id . '/' . $this->order->order_number;

        return OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.not_arrived_yet',
                ['number' => $order_id],
                $notifiable->language
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->language))
            ->setData('type', $type)
            ->setUrl($url)
            ->setData('order_id', $this->order->id)
             ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
            ->setParameter('ios_sound', 'siren.wav')
            ->setParameter('android_sound', 'siren');
    }

    public function toArray($notifiable)
    {
        return [
            "order" => $this->order,
        ];
    }
}
