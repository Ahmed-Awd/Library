<?php

namespace App\Notifications;

use App\Channels\OneSignalRestaurantChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalCustomerChannel;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalWebButton;

class OrderCanceledByRestaurant extends Notification
{
    use Queueable;

    protected Order $order;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function via($notifiable): array
    {
        return ['database',OneSignalCustomerChannel::class,OneSignalRestaurantChannel::class];
    }

    public function toOneSignal($notifiable)
    {

        $data['id'] = $this->order->id;
        $data['order_number'] = $this->order->order_number;
        $type = "OrderCanceledByRestaurant";
        $url = $this->order->id;

        $message = OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.order_status_changes',
                ['number' => $this->order->order_number,'status' => $this->order->orderStatus->name],
                $notifiable->default_lang
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
            ->setData('order', $data)
            ->setData('type', $type)
            ->setData('url', $url);

        if ($notifiable->hasRole('super_admin') || $notifiable->hasRole('admin')) {
            $message = $message->setUrl(config("app.admin_url")."orderDetails/$url")
                ->webButton(
                    OneSignalWebButton::create('link-1')->text('show')
                        ->url(config("app.admin_url")."orderDetails/$url")
                );
        }
        return $message;
            // ->setParameter('android_channel_id', config('services.onesignal.android_channel_id'))
            // ->setParameter('ios_sound', 'siren.wav')
            // ->setParameter('android_sound', 'siren');
    }

    public function toArray($notifiable)
    {
        return [
            "order" => $this->order
        ];
    }
}
