<?php

namespace App\Repositories;

use App\Models\DriverNewOrders;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class OrderRepository implements OrderRepositoryInterface
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function get($filter, $range = false)
    {
        $orders = $this->order
            ->with('restaurant:id,name,min_order_price', 'user:id,name,country_code,phone', 'orderStatus:id,name');

        if (isset($filter['order_status_id'])) {
            $orders = $orders->whereIn('order_status_id', $filter['order_status_id']);
            unset($filter['order_status_id']);
        }
        if (isset($filter['new_order'])) {
            $ids = DriverNewOrders::where('user_id', Auth::user()->id)->pluck('order_id');
            $orders = $orders->whereIn('id', $ids);
            unset($filter['new_order']);
        }
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }

        return $orders->where($filter)->latest('created_at')->paginate(15);
    }

    public function getStats($filter, $range): array
    {
        $orders = $this->order->with('orderStatus:id,name');
        unset($filter['order_status_id']);
        unset($filter['new_order']);
        $orders = $orders->whereIn('order_status_id', [7,8]);
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        $data['total_delivery'] = $orders->where($filter)->where('service_info_type', 1)->sum('total_amount');//nothing
        $data['total_takeaway'] = $orders->where($filter)->where('service_info_type', 0)->sum('total_amount');
        return $data;
    }

    public function report($filter, $range = false)
    {
        $orders = $this->order->Paid()->with([
            'restaurant:id,name,address',
            'user:id,name',
            'driver:id,name',
            'orderStatus:id,name',
            'reason:id,reason',
            'discountCode:id,code']);
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        return $orders->where($filter)->latest('created_at')->paginate(15);
    }

    public function sum($filter, $range = false)
    {
        $orders = $this->order->Paid();
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        return $orders->where($filter)->sum('total_amount');
    }

    public function count($filter)
    {
        $order = $this->order->Paid()->with('restaurant:id,name', 'user:id,name,country_code,phone', 'orderStatus:id,name');
        if ($filter['order_status_id']) {
            $order = $order->whereIn('order_status_id', $filter['order_status_id']);
            unset($filter['order_status_id']);
        }
        return $order->where($filter)->count();
    }

    public function show($order)
    {
        if (Auth::user()->hasRole('owner')) {
            $order->update(['read' => 1]);
        }
        return Order::where('id', $order->id)
            ->with(['orderItem', 'user:id,name,country_code,phone', 'reason:id,reason'
                , 'driver:id,name,country_code,phone', 'orderStatus:id,name',
                 'logStatus','paymentMethod:id,name','payment','refund'])->first();
    }

    public function showWereIn($order)
    {
        return Order::whereIn('id', $order)->with(['orderItems'])->get();
    }

    public function store($data)
    {
        $order = DB::table('orders')
            ->insertGetId(array_merge($data, [
                "created_at" => now(),
            ]));

        return $order;
    }

    public function update($order, $data)
    {
        $order->update($data);
    }


    public function delete($order)
    {
        $order->delete();
    }

    public function cancelOrder($order, $data)
    {
        //return the customer money,send notification to the customer that his order is canceled and his money is back

        //cancel the order
        $order->update(
            [
                "order_status_id" => "9"
                ,"refuse_reason_id" => $data['refuse_reason']
                ,"refuse_comment" => $data["refuse_comment"]
            ]
        );
    }

    public function rate($order, $data): string
    {
        if ($order->order_status_id == 8) {
            return Lang::get('messages.rate_order.already');
        }

        if ($order->order_status_id != 7 || $order->delivered_at == null) {
            return Lang::get('messages.rate_order.still');
        }

        if ($order->delivered_at != null) {
            $time = Carbon::now()->diffInMinutes($order->delivered_at);
            if ($time < 60) {
                $time = 60 - $time;
                return Lang::get('messages.rate_order.wait', ['time' => $time]);
            }
            if ($time > 2880) {
                return Lang::get('messages.rate_order.time_over');
            }
        }


        $order->update([
            "is_rated" => true,
            "rate" => $data["rate"],
            "comment" => $data["comment"] ?? null,
            "order_status_id" => 8,
        ]);

        return Lang::get('messages.rate_order.done');
    }

    public function newOrders($restaurant)
    {
        return $this->order->Paid()->where('order_status_id', 1)
            ->where('read', 0)
            ->where('restaurant_id', $restaurant)->count();
    }

    public function getAll($range, $type, $status, $restaurant, $from, $to)
    {
        $result = $this->order->with([
            'restaurant:id,name,address',
            'user:id,name',
            'driver:id,name',
            'orderStatus:id,name',
            'paymentMethod:id,name',
            'reason:id,reason',
            'discountCode:id,code']);

        if ($range != false) {
            $result = dateFilter($result, $range, 'created_at');
        }
        if ($type != "none") {
            if ($type == "all") {
                //do nothing
            } else {
                $result = $result->where('service_info_type', $type);
            }
        }
        if ($status != false) {
            $result = $result->where('order_status_id', $status);
        }
        if ($restaurant != false) {
            $result = $result->where('restaurant_id', $restaurant);
        }
        if ($from != false && $to != false) {
            $result = $result->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)->addDay()]);
        }
        return $result->orderBy('created_at', 'DESC')->get();
    }

    public function getCanceled($filter, $range)
    {
        $orders = $this->order
            ->with('restaurant:id,name,min_order_price', 'user:id,name,country_code,phone', 'orderStatus:id,name');

        if (isset($filter['order_status_id'])) {
            $orders = $orders->whereIn('order_status_id', $filter['order_status_id']);
            unset($filter['order_status_id']);
        }
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }

        return $orders->where($filter)->latest('created_at')->get();
    }

    public function getAllCount()
    {
        $status = [2,3,4,5,6,7,8];
        $orders = $this->order->Paid();
        $orders = $orders->whereIn('order_status_id', $status);
        return $orders->count();
    }

    public function assignDriver($order, $data)
    {
        $order->update($data);
    }
}
