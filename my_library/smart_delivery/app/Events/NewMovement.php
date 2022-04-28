<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewMovement implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $order;
    public $location;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $location)
    {
        $this->order = $order;
        $this->location = $location;
    }


    public function broadcastOn()
    {
        error_log("orders.{$this->order->id}");
        return new Channel("orders.{$this->order->id}");
    }

    public function broadcastAs(): string
    {
        return 'new-location';
    }

    public function broadcastWith()
    {
        return [
          'order_id' => $this->order->id,
          'order_status' => $this->order->status,
          'driver_lat' => $this->location["lat"],
          'driver_lng' => $this->location["lng"]
        ];
    }
}
