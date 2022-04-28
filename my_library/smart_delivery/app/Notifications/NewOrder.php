<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\Channels\OneSignalStoreChannel;
use App\Channels\OneSignalDriverChannel;

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
        return ['database',OneSignalDriverChannel::class];
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
        $data['store']['lat'] = $this->order->store->lat;
        $data['store']['lng'] = $this->order->store->lng;
        $data['store']['address'] = $this->order->store->address;
        $data['store']['name'] = $this->order->store->name;
        $data['id'] = $this->order->id;
        $data['customer_name'] = $this->order->customer_name;
        $data['delivery_price'] = $this->order->delivery_price;
        $data['total_price'] = $this->order->total_price;
        $data['distance_store_order'] = $this->order->distance_store_order;
        $data['customer_address'] = $this->order->customer_address;
        $data['customer_lat'] = $this->order->customer_lat;
        $data['customer_lng'] = $this->order->customer_lng;
        $data['expected_time'] = $this->order->expected_time;
        $data['customer_payed'] = $this->order->order_price - $this->order->delivery_fee_payed_by_store;
        $type = "NewOrder";
        $url = $this->order->id;

        return OneSignalMessage::create()
            ->setSubject(Lang::get('messages.notifications.new_order', [], $notifiable->language))
            ->setBody(Lang::get('messages.notifications.accept_order', [], $notifiable->language))
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
