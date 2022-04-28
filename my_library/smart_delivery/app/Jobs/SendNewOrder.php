<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrder;
use Illuminate\Support\Facades\Notification;

class SendNewOrder implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::find($this->order);
        //select drivers;
        $records = User::role('driver')->select('id', 'lat', 'lng', 'town_id')->has('tokens')
            ->where('is_available', 1)
            ->where('town_id', $order->town_id);
        //->whereNotNull('lat')
        //->whereNotNull('lng')
        // will return it tomorrow -----------------------------------------------------------------------------
        //$records = setNearestDrivers($records, $order->store->lat, $order->store->lng);
        $users = $records->get();

        if ($order) {
            Notification::send($users, new NewOrder($order));
        }
    }
}
