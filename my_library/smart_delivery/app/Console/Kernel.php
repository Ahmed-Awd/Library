<?php

namespace App\Console;

use App\Models\DriverLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Order;
use App\Models\TestCronJop;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\CanceledOrder;
use App\Notifications\OrderNotAcceptYet;
use App\Notifications\DriverNotArriveRestaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
            $this->deliveredOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->canceledOrder();
        })->everyMinute();
        $schedule->call(function () {
            $this->notifyOrderNotAcceptYet();
        })->everyMinute();
        $schedule->call(function () {
            $this->driverNotArriveRestaurant();
        })->everyMinute();
        $schedule->call(function () {
            $this->clearNotification();
        })->dailyAt('01:00');
        $schedule->call(function () {
            $this->driverLog();
        })->dailyAt('23:55');
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

    public function canceledOrder()
    {
        $orders = Order::where('status', 1)->get();
        foreach ($orders as $order) {
            if ($this->checkTime($order->sent_at, 'request_canceled_if_not_assigned')) {
                $rate = 0;
                $order->update(["status" => 5]) ;
                $user = User::findOrFail($order->store->owner_id);
                $order->store->setting->taken_percentage_from_store == null
                    ? $rate = settings("taken_percentage_from_delivery")
                    : $rate = $order->store->setting->taken_percentage_from_store;

                $value = floor($order->delivery_price * ($rate / 100));
                $user->balance = $user->balance + $value;
                $user->reserved_balance = $user->reserved_balance - $value;
                $user->save();
                $user->notify(new CanceledOrder($order));
            }
        }
    }
    public function notifyOrderNotAcceptYet()
    {
        $orders = Order::where('status', 1)->get();
        foreach ($orders as $order) {
            if ($this->checkTime($order->sent_at, 'admin_notified_if_request_not_assigned')) {
                $users = User::role('admin')->get();
                Notification::send($users, new OrderNotAcceptYet($order));
            }
        }
    }
    public function clearNotification()
    {
        DB::table('notifications')->whereDate('created_at', '<', Carbon::now()->subHours(24))->delete();
    }
    public function driverNotArriveRestaurant()
    {
        $orders = Order::where('status', 2)->get();
        foreach ($orders as $order) {
            if ($this->checkTime($order->updated_at, 'admin_notified_if_driver_not_arrive_at_the_restaurant')) {
                 $users = User::role('admin')->get();
                Notification::send($users, new DriverNotArriveRestaurant($order));
            }
        }
    }

    public function deliveredOrder()
    {
        $orders = Order::where('status', 3)->get();
        foreach ($orders as $order) {
            if ($this->checkTime($order->updated_at, 'order_convert_automatically')) {
                $order->update(["status" => 4]) ;
            }
        }
    }
    public function driverLog()
    {
        $users = User::whereHas("roles", function ($q) {
            $q->where("name", "driver");
        })->get();
        foreach ($users as $user) {
               $driver = new DriverLog();
               $driver->user_id = $user->id;
               $driver->day = now()->format('Y-m-d');
               $driver->online_time = $user->online_time_today;
               $driver->save();
               $user->update(["online_time_today" => 0]) ;
        }
    }
    public function testCronJop()
    {
        $driver = new TestCronJop();
        $driver->name = "test";
        $driver->save();
    }
    public function checkTime($dateTime, $type)
    {
            $time_order = Carbon::now()->diffInMinutes($dateTime);
            $time_setting = settings($type);
           return $time_setting == $time_order ;
    }
}
