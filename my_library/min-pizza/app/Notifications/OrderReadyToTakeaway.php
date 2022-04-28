<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalCustomerChannel;
use Illuminate\Support\Facades\Lang;

class OrderReadyToTakeaway extends Notification
{
    use Queueable;

    protected Order $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function via($notifiable): array
    {
        return ['database',OneSignalCustomerChannel::class];
    }

    public function toOneSignal($notifiable)
    {

        $data['id'] = $this->order->id;
        $data['order_number'] = $this->order->order_number;
        $type = "OrderReadyToTakeaway";
        $url = $this->order->id;

        return OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.order_ready_to_takeaway',
                ['number' => $this->order->order_number],
                $notifiable->default_lang
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
            ->setData('order', $data)
            ->setData('type', $type)
            ->setData('url', $url)
            ->setParameter('android_channel_id', config('services.onesignalcustomer.android_channel_id'))
            ->setParameter('ios_sound', 'siren.wav')
            ->setParameter('android_sound', 'siren');
    }

    public function toArray($notifiable)
    {
        return [
            "order" => $this->order
        ];
    }
}
