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

class OrderAcceptedByDriver extends Notification
{
    use Queueable;

    private Order $order;
    private User $user;

    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user  = $user;
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
        $type = "AcceptOrder";
        $url = $this->order->id;

        return OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.driver_coming',
                ['driver' => $this->user->name],
                $notifiable->language
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->language))
            ->setData('order_id', $this->order->id)
            ->setData('type', $type)
            ->setData('url', $url)
            ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
            ->setParameter('ios_sound', 'siren.wav')
            ->setParameter('android_sound', 'siren');
    }

    public function toArray($notifiable)
    {
        return [
            "order" => $this->order,
            "driver" => $this->user
        ];
    }
}
