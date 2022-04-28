<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Services\GeoDistance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function get($filter, $orderBy, $range = false, $report = false, $withoutPagination = false)
    {
        $orders = $this->order;
        if (isset($filter['status'])) {
            $orders = $orders->whereIn('status', $filter['status']);
            unset($filter['status']);
        }
        if ($report == true) {
            $orders = $orders->select(
                'id',
                'order_number',
                'store_id',
                'total_price',
                'delivery_price',
                'status',
                'rate',
                'driver_id',
                'created_at',
                'sent_at',
                'driver_fee',
                'store_fee',
                'delivery_fee_payed_by_store',
                'distance_store_order',
            );
        }
        $orders = $orders->with(['orderStatus', 'driver:id,name', 'store:id,name,address,lat,lng']);
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        $orders = $orders->where($filter)->latest($orderBy);
        $totals = $this->getTotals($orders);
        $withoutPagination == true ? $orders = $orders->get()
            : $orders = $orders->paginate(15)->appends(request()->query());

        foreach ($orders as $order) {
            $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store ;
        }

        if ($report == true && $withoutPagination == false) {
            $final = collect($totals);
            $orders = $final->merge($orders);
        }

        if ($report == true && ($withoutPagination == true || $withoutPagination == 'true')) {
            $final = [];
            $final['orders'] = $orders;
            $final['totals'] = $totals;
            return $final;
        }

        return $orders;
    }

    public function getTotals($orders)
    {
        $totals = [];
        $totals['total_price'] = (int)$orders->sum('total_price');
        $totals['delivery_price'] = (int)$orders->sum('delivery_price');
        $totals['store_fee'] = (int)$orders->sum('store_fee');
        $totals['driver_fee'] = (int)$orders->sum('driver_fee');
        return $totals;
    }

    public function driverNewOrders($filter, $orderBy)
    {
        $orders = $this->order->select()->with(['orderStatus', 'driver:id,name', 'store:id,name,address,lat,lng']);
        if (isset($filter['status'])) {
            $orders = $orders->whereIn('status', $filter['status']);
            unset($filter['status']);
        }
        $my = User::find(Auth::id());
        $orders = $orders->where($filter)->where('town_id', $my->town_id);
        //get orders near you
//        if ($my->lat != null && $my->lng != null) {
//            $orders = setNearestOrders($orders, $my->lat, $my->lng);
//        } else {
//            return $this->order->where('id', 0)->paginate(15);
//        }
        $orders = $orders->latest($orderBy)->paginate(15);
        foreach ($orders as $order) {
            $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store ;
        }

        return $orders;
    }

    public function count($filter)
    {
        $order = $this->order->with(['orderStatus', 'driver:id,name', 'store:id,name,address,lat,lng']);
        if ($filter['status']) {
            $order = $order->whereIn('status', $filter['status']);
            unset($filter['status']);
        }
        return $order->where($filter)->count();
    }

    public function store($store, $inputData, $status, $deliveryPrice, $storePayed, $distance, $storeFee)
    {
        $lastOrder = $store->orders()->latest()->first();
        $inputData['customer_lat'] = $inputData['location']['lat'];
        $inputData['customer_lng'] = $inputData['location']['lng'];
        $inputData['comment'] = $inputData['comment'] ?? '';
        $inputData['total_price'] = $inputData['total_price'] * 100;
        $inputData['sent_at'] = $status == 1 ? now() : null;
        $inputData['store_fee'] = $storeFee;
        unset($inputData['location']);
        unset($inputData['distance']);
        $order = DB::table('orders')->insertGetId(array_merge($inputData, [
            'order_number' => $lastOrder ? $lastOrder->order_number + 1 : 1,
            "store_id" => $store->id,
            "store_lat" => $store->lat,
            "store_lng" => $store->lng,
            "town_id" => $store->town->id,
            "rate" => null,
            "delivery_price" => $deliveryPrice,
            "delivery_fee_payed_by_store" => $storePayed,
            "distance_store_order" => $distance,
            "qr_code" => rand(1000, 9999),
            "status" => $status,
            "created_at" => now(),
        ]));

        return $order;
    }

    public function reorder($store, $order, $status, $deliveryPrice, $storePayed, $storeFee)
    {
        $lastOrder = $store->orders()->latest()->first();
        $inputData['customer_name'] = $order->customer_name;
        $inputData['customer_lat'] = $order->customer_lat;
        $inputData['customer_lng'] = $order->customer_lng;
        $inputData['customer_address'] = $order->customer_address;
        $inputData['customer_mobile'] = $order->customer_mobile;
        $inputData['expected_time'] = $order->expected_time;
        $inputData['building_no'] = $order->building_no;
        $inputData['apartment_no'] = $order->apartment_no;
        $inputData['comment'] = $order->comment;
        $inputData['total_price'] = $order->total_price;
        $inputData['distance_store_order'] = $order->distance_store_order;
        $inputData['sent_at'] = $status == 1 ? now() : null;
        $inputData['store_fee'] = $storeFee;
        $order = DB::table('orders')->insertGetId(array_merge($inputData, [
            'order_number' => $lastOrder ? $lastOrder->order_number + 1 : 1,
            "store_id" => $store->id,
            "store_lat" => $store->lat,
            "store_lng" => $store->lng,
            "town_id" => $store->town ? $store->town->id : null,
            "delivery_price" => $deliveryPrice,
            "delivery_fee_payed_by_store" => $storePayed,
            "qr_code" => rand(1000, 9999),
            "status" => $status,
            "created_at" => now(),
        ]));

        return $order;
    }

    public function rate($order, $data)
    {
        if ($order->status != 4 || $order->rate > 0) {
            return;
        }

        $order->update([
            "rate" => $data["rate"],
        ]);
    }

    public function delete($order)
    {
        $order->delete();
    }

    public function changeStatus($order, $status)
    {
        $order->status = 1;
        $order->sent_at = now();
        $order->save();
    }
    public function update($order, $data)
    {
        $order->update($data);
    }

    public function history($range = false, $user = false)
    {
        if ($user == false) {
            $user = Auth::user();
        }
        $records = [];

        if ($user->hasRole('driver')) {
            $records =  $user->driver()->join('stores', 'stores.id', '=', 'orders.store_id')
                ->select(
                    'orders.created_at',
                    DB::raw("orders.driver_fee as value"),
                    'stores.name as store_name'
                )
                ->addSelect(DB::raw("'outgoing' as status"));

            $records = dateFilter($records, $range, 'orders.created_at');
        }
        if ($user->hasRole('owner')) {
            $records = $user->store->orders()
                ->select('created_at', DB::raw("store_fee as value"))
                ->addSelect(DB::raw("'' as store_name"))
                ->addSelect(DB::raw("'outgoing' as status"));

            $records = dateFilter($records, $range, 'orders.created_at');
        }
        return $records;
    }

    public function getTotalFee($filter, $range = false)
    {
        $orders = $this->order;
        if (isset($filter['status'])) {
            $orders = $orders->whereIn('status', $filter['status']);
            unset($filter['status']);
        }

        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        $amount = $orders->where($filter)->select(DB::raw('sum(driver_fee + store_fee) as smart_total_income'))->get();
        return $amount;
    }

    public function getTotalOutgoing($range = false, $user = false)
    {
        if ($user == false) {
            $user = Auth::user();
        }
        $records = [];
        $total = 0;
        if ($user->hasRole('owner')) {
            $records = $user->store->orders();
            if ($range) {
                $records = dateFilter($records, $range, 'orders.created_at');
            }
            $total = $records->sum('store_fee');
        }

        if ($user->hasRole('driver')) {
            $records = $user->driver();
            if ($range) {
                $records = dateFilter($records, $range, 'orders.created_at');
            }
            $total = $records->sum('driver_fee');
        }
        return $total;
    }

    public function getAll($filter, $orderBy, $range = false, $report = false)
    {
        $orders = $this->order;
        if (isset($filter['status'])) {
            $orders = $orders->whereIn('status', $filter['status']);
            unset($filter['status']);
        }
        if ($report == true) {
            $orders = $orders->select(
                'id',
                'order_number',
                'store_id',
                'total_price',
                'delivery_price',
                'status',
                'rate',
                'driver_id',
                'created_at',
                'sent_at',
                'driver_fee',
                'store_fee',
                'delivery_fee_payed_by_store',
                'distance_store_order',
            );
        }
        $orders = $orders->with(['orderStatus', 'driver:id,name,phone,country_code', 'store:id,name,address,lat,lng']);
        if ($range) {
            $orders = dateFilter($orders, $range, 'created_at');
        }
        $orders = $orders->where($filter)->latest($orderBy);
        $data['totals'] = $this->getTotals($orders);
        $data['orders'] = $orders->get();
        $data['count']  = $orders->count();

        foreach ($orders as $order) {
            $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store ;
        }

        return $data;
    }

    public function archive($of, $type = "all", $id = false)
    {
        $user = Auth::user();
        $of == "driver" ? $coulmn = "driver_archived" : $coulmn = "owner_archived";
        if ($type == "all") {
            if ($user->hasRole('driver')) {
                $this->order->where('created_at', '<', Carbon::now()->subDay())
                    ->where($coulmn, false)
                    ->where('driver_id', $user->id)
                    ->update([$coulmn => true]);
            }
            if ($user->hasRole('owner')) {
                $this->order->where('created_at', '<', Carbon::now()->subDay())
                    ->where($coulmn, false)
                    ->where('store_id', $user->store->id)
                    ->update([$coulmn => true]);
            }
        }
        if ($type != "all" && $id != false) {
            $this->order->where('id', $id)->update([$coulmn => true]);
        }
    }

    public function show($order)
    {
        return $this->order->where('id', $order)
            ->select(
                'id',
                'order_number',
                'store_id',
                'driver_id',
                'store_lat',
                'store_lng',
                'customer_lat',
                'customer_lng',
                'customer_address',
                'building_no',
                'apartment_no',
                'customer_name',
                'customer_mobile'
            )
            ->with(['driver:id,name,lat,lng','store:id,name'])->first();
    }
}
