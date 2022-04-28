<?php

namespace App\Notifications;

use App\Channels\OneSignalCustomerChannel;
use App\Channels\OneSignalDriverChannel;
use App\Channels\OneSignalRestaurantChannel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class OrderIsLate extends Notification
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
        return ['mail',OneSignalCustomerChannel::class,OneSignalRestaurantChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $message =  OneSignalMessage::create()
            ->setSubject(Lang::get(
                'messages.notifications.late',
                ['number' => $this->order->order_number],
                $notifiable->default_lang
            ))
            ->setBody(Lang::get('messages.notifications.more', [], $notifiable->default_lang))
            ->setData('order', $this->order);

        if ($notifiable->hasRole('super_admin') || $notifiable->hasRole('admin')) {
            $message = $message->setUrl(config("app.admin_url")."orderDetails/{$this->order->id}")
                ->webButton(OneSignalWebButton::create('link-1')
                        ->text('show')
                        ->url(config("app.admin_url")."orderDetails/{$this->order->id}"));
        }
        return $message;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
