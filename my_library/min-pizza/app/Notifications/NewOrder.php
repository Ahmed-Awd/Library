<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalRestaurantChannel;
use Illuminate\Support\Facades\Lang;

class NewOrder extends Notification
{
    use Queueable;

    protected Order $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function via($notifiable): array
    {
        return ['database',OneSignalRestaurantChannel::class];
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

        $data['id'] = $this->order->id;
        $data['order_number'] = $this->order->order_number;
        $type = "NewOrder";
        $url = $this->order->id;
        if ($this->order->is_created_by_admin) {
            $subject = Lang::get(
                'messages.notifications.new_order_from_admin',
                ['number' => $this->order->order_number],
                $notifiable->default_lang
            );
        } else {
            $subject = Lang::get(
                'messages.notifications.new_order',
                ['number' => $url],
                $notifiable->default_lang
            );
        }

        return OneSignalMessage::create()
            ->setSubject($subject)
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
            ->setData('order', $data)
            ->setData('type', $type)
            ->setData('url', $url)
            ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
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
