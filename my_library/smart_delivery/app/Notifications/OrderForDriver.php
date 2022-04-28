<?php

namespace App\Notifications;

use App\Channels\OneSignalDriverChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalMessage;

class OrderForDriver extends Notification
{
    use Queueable;

    protected Order $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail',OneSignalDriverChannel::class];
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
        $data = $this->order;
        $type = "SpecialOrder";
        $url = $this->order->id;
        return OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.special_order_change',
                ['number' => $this->order->order_number],
                $notifiable->language
            ))
            ->setBody(Lang::get('messages.notifications.special_order_change', [], $notifiable->language))
            ->setData('order', $data)
            ->setData('type', $type)
            ->setData('url', $url)
            ->setParameter('android_channel_id', config('services.onesignaldriver.android_channel_id'))
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
