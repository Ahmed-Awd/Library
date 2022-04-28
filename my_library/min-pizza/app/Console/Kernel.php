<?php

namespace App\Console;

use App\Models\Holiday;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\TestCronJop;
use App\Models\User;
use App\Notifications\ChangeStatusRestaurant;
use App\Notifications\NewOrder;
use App\Notifications\NewOrderDriver;
use App\Notifications\OrderCanceledByRestaurant;
use App\Notifications\OrderNotAcceptByDriver;
use App\Notifications\OrderNotAcceptYet;
use App\Notifications\SchedulingOrder;
use App\Repositories\DriverRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;
use Exception;

class Kernel extends ConsoleKernel
{


    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $this->openRestaurant();
        })->everyMinute();
        $schedule->call(function () {
            $this->schedulingOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->canceledOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->noActionOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->unholdUser();
        })->everyMinute();
        $schedule->call(function () {
            $this->holiday();
        })->everyThirtyMinutes();
        $schedule->call(function () {
            $this->changeStatusRestaurant();
        })->everyMinute();
        $schedule->call(function () {
            $this->noDriverOrder();
        })->everyThreeMinutes();
        $schedule->call(function () {
            $this->changstatusUser();
        })->everyThreeMinutes();
        $schedule->call(function () {
            $this->changstatusOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->deleteOrderNotPaid();
        })->dailyAt('23:50');
        $schedule->call(function () {
            $this->testCronJop();
        })->everyTwoHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    public function holiday()
    {
        $restaurant_ids = Holiday::where('day', '=', date('d'))
        ->where('month', '=', date('m'))->pluck('restaurant_id')->all();
        $restaurants = Restaurant::whereIn('id', $restaurant_ids)->where('status_id', 1)->get();
        foreach ($restaurants as $restaurant) {
                $restaurant->update(["status_id" => 2]) ;
                $current = Restaurant::where('id', $restaurant->id)->first();
            if ($restaurant->owner->tokens->count()) {
                $restaurant->owner->notify(new ChangeStatusRestaurant($current));
            }
        }
    }
    public function openRestaurant()
    {
        $restaurants = Restaurant::where('status_id', 3)->whereNotNull('closing_at')->get();
        foreach ($restaurants as $restaurant) {
            if ($this->checkTime($restaurant->closing_at, $restaurant->closing_time)) {
                $restaurant->update(["status_id" => 1]) ;
                $current = Restaurant::where('id', $restaurant->id)->first();
                if ($restaurant->owner->tokens->count()) {
                    $restaurant->owner->notify(new ChangeStatusRestaurant($current));
                }
            }
        }
    }
    public function changstatusUser()
    {
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "owner");
        })->whereHas("restaurant", function ($q) {
            $q->where("mode", "online");
        })
        ->whereNotNull('last_seen')->get();
        foreach ($users as $user) {
            if (Carbon::now()->diffInMinutes($user->last_seen) > 6) {
                if($user->restaurant)
                $user->restaurant->update([
                    'mode'=>'offline'
                ]);
            }
        }
    }
    public function changeStatusRestaurant()
    {
        $restaurant_ids = Holiday::where('day', '=', date('d'))
        ->where('month', '=', date('m'))->pluck('restaurant_id')->all();
        $restaurants = Restaurant::whereNotIn('id', $restaurant_ids)->where('status_id', '!=', 3)->get();
        foreach ($restaurants as $restaurant) {
            if ($this->isOpenRestaurant($restaurant)) {
                if ($restaurant->status_id==2) {
                    $restaurant->update(["status_id" => 1]) ;
                    $current = Restaurant::where('id', $restaurant->id)->first();
                    if ($restaurant->owner->tokens->count()) {
                        $restaurant->owner->notify(new ChangeStatusRestaurant($current));
                    }
                }
            } else {
                if ($restaurant->status_id==1) {
                    $restaurant->update(["status_id" => 2]) ;
                    $current = Restaurant::where('id', $restaurant->id)->first();
                    if ($restaurant->owner->tokens->count()) {
                        $restaurant->owner->notify(new ChangeStatusRestaurant($current));
                    }
                }
            }
        }
    }
    public function schedulingOrder()
    {
        $driverRepository = $this->app->make(DriverRepositoryInterface::class);

        $orders = Order::where('order_status_id', 2)->where('scheduling', 1)->whereNotNull('scheduling_to')->get();
        foreach ($orders as $order) {
            if ($this->checkTime(
                $order->scheduling_to,
                $this->settings('time_for_send_scheduling_order_notfication')
            )
            ) {
                if($order->service_info_type == 1)
                {
                    $restaurant=$order->restaurant;
                    $filter['restaurant_id'] = $restaurant->id;
                    $filter['is_active'] = 1;
                    $filter['order'] = $order;
                    $drivers = $driverRepository->getByFilter($filter);
                    info($drivers);
                    if (count($drivers) != 0) {
                        foreach ($drivers as $driver) {
                            if ($driver->user->tokens->count()) {
                                try {
                                    $driver->user->notify(new NewOrderDriver($order));
                                } catch (Exception $ex) {
                                }
                            }
                        }
                    } else {
                        $filter2['is_active'] = 1;
                        $filter2['type'] = 'app';
                        $filter2['order'] = $order;
                        $filter2['restaurant'] = $restaurant;
                        $drivers = $driverRepository->getByFilter($filter2);
                        foreach ($drivers as $drive) {
                            if ($drive->user->tokens->count()) {
                                try {
                                    $drive->user->notify(new NewOrderDriver($order));
                                } catch (Exception $ex) {
                                }
                            }
                        }
                    }
                }

                if ($order->restaurant->owner->tokens->count()) {
                    $order->restaurant->owner->notify(new SchedulingOrder($order));
                }
            }
        }
    }
    public function unholdUser()
    {
        $users = User::where('is_disabled', 1)->whereNotNull('hold_to')->get();
        foreach ($users as $user) {
            if (!Carbon::now()->diffInMinutes($user->hold_to)
            ) {
                $user->is_disabled = 0;
                $user->hold_to = null;
                $user->save();
            }
        }
    }
    public function changstatusOrder()
    {
        $orders = Order::where('id',183)->where('paid', 0)->get();
        foreach ($orders as $order) {
            if ($order->payment_method ==1 )
            {
                $order->update(["paid" => 1]) ;
            }else if ($order->payment_method ==2 )
            {
                if($order->payment)
                {
                    if($order->payment->data)
                    {
                        if( $order->payment->data->status == 'PAID')
                        {
                            $order->update(["paid" => 1]);
                            if ($order->restaurant->owner->tokens->count()) 
                            {
                                $order->restaurant->owner->notify(new NewOrder($order));
                            }
                        }
                        else if( $order->payment->data->status == 'CREATED')
                        {
                            $instructionUUID=$order->payment->uuid;
                            $url='https://cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests/'.$instructionUUID;
                          $response2 =swish($url, array(), 'GET');
                            if ($response2['status']==200) {
                                if($response2['response']->status=='PAID')
                                {
                                    $order->payment->data=json_encode($response2['response']);
                                    $order->payment->save();
                                    $order->update(["paid" => 1]);
                                    if ($order->restaurant->owner->tokens->count()) 
                                    {
                                        $order->restaurant->owner->notify(new NewOrder($order));
                                    }
                                }
                               
                            }
                        }
                             
                    }
                    
                }
            }else if($order->payment_method ==3)
            {
                if($order->payment)
                {
                    $order->update(["paid" => 1]);
                    if ($order->restaurant->owner->tokens->count()) 
                    {
                        $order->restaurant->owner->notify(new NewOrder($order));
                    }
                }
            }
        }
    }

    public function deleteOrderNotPaid()
    {
        Order::where('paid', 0)->whereDate('created_at', '<', Carbon::now()->subHours(24))->delete();
    }

    public function canceledOrder()
    {
        $orders = Order::where('order_status_id', 1)->get();
        foreach ($orders as $order) {
            if ($this->checkTime(
                $order->created_at,
                $this->settings('cancel_the_no_action_order')
            )
            ) {
                $order->update(["order_status_id" => 9]) ;
                if ($order->user->tokens->count()) {
                    $order->user->notify(new OrderCanceledByRestaurant($order));
                }
                $admins = User::permission('view orders')->has('tokens')->get();
                Notification::send($admins, new OrderCanceledByRestaurant($order));
            }
        }
    }
    public function noActionOrder()
    {
        $orders = Order::where('order_status_id', 1)->get();
        foreach ($orders as $order) {
            if ($this->checkTime(
                $order->created_at,
                $this->settings('no_action_taken_for_order')
            )
            ) {
                if ($order->restaurant->owner->tokens->count()) {
                    $order->restaurant->owner->notify(new OrderNotAcceptYet($order));
                }
                $admins = User::permission('view orders')->has('tokens')->get();
                Notification::send($admins, new OrderNotAcceptYet($order));
            }
        }
    }

    public function noDriverOrder()
    {
        $orders = Order::where('order_status_id', 2)->whereNotNull('updated_at')->where('scheduling', 0)->get();
        foreach ($orders as $order) {
            if ($this->checkTime($order->updated_at, $this->settings('time_for_send_no_driver_notification'))) {
                $admins = User::permission('view orders')->has('tokens')->get();
                Notification::send($admins, new OrderNotAcceptByDriver($order));
            }
        }
    }

    public function checkTime($dateTime, $time)
    {
            $time_closing = $dateTime->diffInMinutes(Carbon::now());
           return $time_closing == $time ;
    }

    public function settings($key)
    {
        return Setting::firstWhere('key', $key)->value;
    }
    public function isOpenRestaurant($restaurant)
    {
           $day= getCurrentDayNumber(Carbon::now()->dayName);
              $open = $restaurant->workTime()
              ->where('day_id', '=', $day)
              ->where('open_from', '<', Carbon::now()->toTimeString())
              ->where('open_to', '>', Carbon::now()->toTimeString())
              ->where('status', '=', 1)->first();
        if ($open) {
            return true;
        } else {
            return false;
        }
    }
    public function testCronJop()
    {
        $driver = new TestCronJop();
        $driver->name = "test";
        $driver->save();
    }
}
