<?php

namespace App\Notifications;

use App\Channels\OneSignalCustomerChannel;
use App\Channels\OneSignalDriverChannel;
use App\Channels\OneSignalRestaurantChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalMessage;

class GeneralNotification extends Notification
{
    use Queueable;

    protected string $subject;
    protected string $body;
    private $record;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $body, $record)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->record = $record;
    }


    public function via($notifiable)
    {
        return ['mail',OneSignalCustomerChannel::class,OneSignalDriverChannel::class,OneSignalRestaurantChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        $type = "generalNotification";
        return OneSignalMessage::create()
            ->setSubject($this->subject)
            ->setBody($this->body)
            ->setData('type', $type)
            ->setData('url', $this->record->id)
            ->setData('subject', $this->subject)
            ->setData('body', $this->body);
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
