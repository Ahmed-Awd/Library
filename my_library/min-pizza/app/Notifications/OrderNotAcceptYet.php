<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalRestaurantChannel;
use NotificationChannels\OneSignal\OneSignalWebButton;

class OrderNotAcceptYet extends Notification
{
    use Queueable;

    private Order $order;

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
        $type = "OrderNotAcceptYet";
        $url = $this->order->id;

        $message = OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.not_accepted_yet',
                ['number' => $this->order->order_number],
                $notifiable->language
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->language))
            ->setData('type', $type)
            ->setData('url', $url)
            ->setData('order_id', $this->order->id)
            ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
            ->setParameter('ios_sound', 'siren.wav')
            ->setParameter('android_sound', 'siren');

        if ($notifiable->hasRole('super_admin') || $notifiable->hasRole('admin')) {
            $message = $message->setUrl(config("app.admin_url")."orderDetails/$url")
                ->webButton(OneSignalWebButton::create('link-1')
                    ->text('show')
                    ->url(config("app.admin_url")."orderDetails/$url"));
        }
        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            "order" => $this->order,
        ];
    }
}
