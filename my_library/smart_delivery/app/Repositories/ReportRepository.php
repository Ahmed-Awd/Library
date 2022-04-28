<?php

namespace App\Repositories;

use App\Models\DriverLog;
use App\Models\Order;
use App\Models\PackageCode;
use App\Models\Store;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    private User $user;
    private Order $order;
    private Store $store;
    private DriverLog $driverLog;

    public function __construct(User $user, Order $order, Store $store, DriverLog $driverLog)
    {
        $this->user = $user;
        $this->order = $order;
        $this->store = $store;
        $this->driverLog = $driverLog;
    }

    public function driversReport($filter, $range = false, $status = false)
    {
        $drivers = $this->user->role('driver')->select('id', 'name', 'email', 'country_code', 'phone', 'last_activity')
            ->withCount(['driver' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }])
            ->withSum(['driver' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'driver_fee')
            ->withSum(['driver' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'total_price')
            ->withSum(['driver' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'delivery_price')
            ->withAvg(['driver' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'rate')
            ->orderBy('last_activity', 'desc');
        if ($filter != false || $filter != "") {
            $drivers->where('name', 'like', '%' . $filter . '%')->orWhere('phone', 'like', '%' . $filter . '%');
        }
        $drivers = $drivers->get();
        foreach ($drivers as $driver) {
            $driver->orders_count = $driver->driver_count;
            $driver->drivers_sum_total_price == null ? $driver->drivers_sum_total_price = 0 : 1 == 1;
            $driver->drivers_sum_delivery_price == null ? $driver->drivers_sum_delivery_price = 0 : 1 == 1;
            $driver->driver_sum_driver_fee == null
                ? $driver->total_fee = 0 : $driver->total_fee = $driver->driver_sum_driver_fee;
            $driver->avarage_rate = $driver->driver_avg_rate;
        }
        $data['drivers'] = $drivers;
        $data['totals'] = $this->getDriverTotals($drivers);
        return $data;
    }

    public function getDriverTotals($drivers)
    {
        $totals = [];
        $totals['total_price'] = (int)$drivers->sum('driver_sum_total_price');
        $totals['delivery_price'] = (int)$drivers->sum('driver_sum_delivery_price');
        $totals['orders_count'] = (int)$drivers->sum('driver_count');
        $totals['driver_fee'] = (int)$drivers->sum('driver_sum_driver_fee');
        return $totals;
    }

    public function storesReport($filter, $range = false, $status = false)
    {
        $stores = $this->store->select('id', 'name', 'owner_id')
            ->with('owner:id,name,email', 'setting:id,store_id,taken_percentage_from_store')
            ->withCount(['orders' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }])
            ->withSum(['orders' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'store_fee')
            ->withSum(['orders' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'total_price')
            ->withSum(['orders' => function ($query) use ($range, $status) {
                $query = dateFilter($query, $range, 'created_at');
                if ($status != false) {
                    $query = $query->whereIn('status', $status);
                }
            }], 'delivery_price');
        if ($filter != false || $filter != "") {
            $stores->where('name', 'like', '%' . $filter . '%');
            $stores = $stores->orWhereHas('owner', function ($query) use ($filter) {
                return $query->where('name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('email', 'LIKE', '%' . $filter . '%');
            });
        }
        $stores = $stores->get();

        foreach ($stores as $store) {
            $store->orders_sum_store_fee == null
                ? $store->total_fee = 0 : $store->total_fee = $store->orders_sum_store_fee;
            $store->orders_sum_total_price == null ? $store->orders_sum_total_price = 0 : 1 == 1;
            $store->orders_sum_delivery_price == null ? $store->orders_sum_delivery_price = 0 : 1 == 1;
            if ($store->setting == null) {
                $store->taken_from_percentage = settings("taken_percentage_from_delivery");
            } else {
                $store->setting->taken_percentage_from_store != null
                    ? $store->taken_from_percentage = $store->setting->taken_percentage_from_store
                    : $store->taken_from_percentage = settings("taken_percentage_from_delivery");
            }
        }
        $data['stores'] = $stores;
        $data['totals'] = $this->getStoreTotals($stores);
        return $data;
    }

    public function getStoreTotals($stores)
    {
        $totals = [];
        $totals['total_price'] = (int)$stores->sum('orders_sum_total_price');
        $totals['delivery_price'] = (int)$stores->sum('orders_sum_delivery_price');
        $totals['orders_count'] = (int)$stores->sum('orders_count');
        $totals['store_fee'] = (int)$stores->sum('orders_sum_store_fee');
        return $totals;
    }

    public function topOnline($day)
    {
        $users = [];
        if ($day == "today") {
            $users = $this->user->role('driver')->select('id', 'name', 'last_time_online', 'online_time_today')
                ->withCount(['driver' => function ($query) use ($day) {
                    $query = dateFilter($query, "today", 'created_at');
                }])->orderBy('online_time_today', 'desc')->orderBy('driver_count', 'desc')->get();
        } else {
            $users = $this->driverLog->select('id', 'user_id', 'day', 'online_time as online_time_today')
                ->with(['user' => function ($query) use ($day) {
                    $query->withCount(['driver' => function ($query) use ($day) {
                        $query->whereDate('created_at', $day);
                    }]);
                }])
                ->where('day', $day)
                ->orderBy('online_time', 'desc')->get();
            foreach ($users as $user) {
                $user->name = $user->user->name;
                $user->driver_count = $user->user->driver_count;
            }
        }
        return $users;
    }

    public function orderMovements($filter, $noTime, $range, $fromTime, $toTime, $by)
    {
        $orders = $this->order->has('store')
            ->select('id', 'order_number', 'store_id', 'driver_id', 'distance_store_order');
        $orders = $orders->addSelect(
            'created_at',
            'accepted_by_driver_at',
            'order_delivered_at',
            'order_taken_from_store_at'
        );
        $orders = $orders->with('driver:id,name', 'store:id,name');
        $orders = dateFilter($orders, $range, 'created_at');
        $orders = $orders->where($filter);
        if ($noTime == "false" || $noTime == false) {
            if ($fromTime !== false && $toTime !== false && $by !== false) {
                $orders = $this->timeFilter($orders, $by, $fromTime, $toTime);
            }
        }
        $orders = $this->addTimeDiff($orders);
        $data["orders"] = $orders->orderBy('created_at', 'desc')->get();
        $data["averages"] = $this->calcAverages($data["orders"]->toArray());
        return $data;
    }

    function calcAverages($orders)
    {
        $averages = [];
        $count = count($orders);
        if ($count == 0) {
            $averages["accepting_time"] = 0;
            $averages["taking_time"] = 0;
            $averages["deliver_time"] = 0;
            $averages["total_time"] = 0;
            return $averages;
        }
        $averages["accepting_time"] = array_sum(array_column($orders, 'accepting_time')) / $count;
        $averages["taking_time"] = array_sum(array_column($orders, 'taking_time')) / $count;
        $averages["deliver_time"] = array_sum(array_column($orders, 'deliver_time')) / $count;
        $averages["total_time"] = array_sum(array_column($orders, 'total_time')) / $count;
        return $averages;
    }

    public function addTimeDiff($orders)
    {
        $orders = $orders
            ->addSelect(DB::raw("ABS(TIMESTAMPDIFF(MINUTE, created_at, accepted_by_driver_at)) as accepting_time"))
            ->addSelect(DB::raw("ABS(TIMESTAMPDIFF(MINUTE, accepted_by_driver_at, order_taken_from_store_at))
             as taking_time"))
            ->addSelect(DB::raw("ABS(TIMESTAMPDIFF(MINUTE, order_taken_from_store_at, order_delivered_at))
             as deliver_time"))
            ->addSelect(DB::raw("ABS(TIMESTAMPDIFF(MINUTE, created_at, order_delivered_at))   as total_time"));
        return $orders;
    }

    public function timeFilter($orders, $by, $from, $to)
    {
        if ($by == "accepted_by_driver_at") {
            $orders = $orders->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, created_at, accepted_by_driver_at)) <= {$to}")
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, created_at, accepted_by_driver_at)) >= {$from}");
        }
        if ($by == "order_taken_from_store_at") {
            $orders = $orders
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, accepted_by_driver_at, order_taken_from_store_at)) <= $to")
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, accepted_by_driver_at, order_taken_from_store_at)) >= $from");
        }
        if ($by == "order_delivered_at") {
            $orders = $orders
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, order_taken_from_store_at, order_delivered_at)) <= $to")
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, order_taken_from_store_at, order_delivered_at)) >= $from");
        }
        if ($by == "all_way_long") {
            $orders = $orders
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, created_at, order_delivered_at)) <= $to")
                ->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, created_at, order_delivered_at)) >= $from");
        }
        return $orders;
    }

    public function storesCharge($from, $to)
    {
        $period = CarbonPeriod::create($from, $to)->toArray();
        $stores = $this->store->select('id', 'name', 'owner_id')->get();
        $totals = [];
        $totals["total"] = 0;
        $i = 2;
        foreach ($stores as $store) {
            if (isset($totals[$store->name])) {
                $store->name = $store->name . "_$i";
                $i++;
            }
            $totals[$store->name] = 0;
        }
        $rows = [];
        foreach ($period as $day) {
            $temp = PackageCode::whereDate('created_at', '=', $day->format('Y-m-d'))->get();
            foreach ($stores as $store) {
                $rows[$day->format('Y-m-d')][$store->name] =
                    (int)$temp->where('user_id', $store->owner_id)->sum('value');
                $totals[$store->name] += $rows[$day->format('Y-m-d')][$store->name];
            }
            $rows[$day->format('Y-m-d')]["total"] = array_sum($rows[$day->format('Y-m-d')]);
            $totals["total"] += $rows[$day->format('Y-m-d')]["total"];
        }
        $data['period'] = $period;
        $stores = $stores->pluck('name')->toArray();
        $this->filterZeros($stores, $totals, $period, $rows);
        $data['stores'] = array_values($stores);
        $data['rows'] = $rows;
        $data['totals'] = $totals;
        return $data;
    }

    public function storesOrders($from, $to, $status = false)
    {
        $period = CarbonPeriod::create($from, $to)->toArray();
        $stores = $this->store->select('id', 'name', 'owner_id')->get();
        $totals = [];
        $totals["total"] = 0;
        $rows = [];
        $i = 2;
        foreach ($stores as $store) {
            if (isset($totals[$store->name])) {
                $store->name = $store->name . "_$i";
                $i++;
            }
            $totals[$store->name] = 0;
        }

        $orders = $this->order;
        if ($status != false) {
            $orders = $orders->whereIn('status', $status);
        }
        foreach ($period as $day) {
            $temp = $orders->whereDate('created_at', '=', $day->format('Y-m-d'))->get();
            foreach ($stores as $store) {
                if (isset($rows[$day->format('Y-m-d')][$store->name])) {
                    $store->name = $store->name . "_2";
                }
                $rows[$day->format('Y-m-d')][$store->name] = (int)$temp->where('store_id', $store->id)->count();
                $totals[$store->name] += $rows[$day->format('Y-m-d')][$store->name];
            }
            $rows[$day->format('Y-m-d')]["total"] = array_sum($rows[$day->format('Y-m-d')]);
            $totals["total"] += $rows[$day->format('Y-m-d')]["total"];
        }

        $data['period'] = $period;
        $stores = $stores->pluck('name')->toArray();
        $this->filterZeros($stores, $totals, $period, $rows);
        $data['stores'] = array_values($stores);
        $data['rows'] = $rows;
        $data['totals'] = $totals;
        return $data;
    }

    public function filterZeros(&$stores, &$totals, &$period, &$rows)
    {
        foreach ($stores as $key => $value) {
            if ($totals[$value] == 0) {
                unset($stores[$key]);
                unset($totals[$value]);
                foreach ($period as $day) {
                    unset($rows[$day->format('Y-m-d')][$value]);
                }
            }
        }
    }

    public function generalOrdersReport($from, $to, $status = false)
    {
        $data = [];
        $totals = $this->initTotals();
        $period = CarbonPeriod::create($from, $to)->toArray();
        $freeLancers = $this->user->role('driver')->where('driver_type', 'freelancer')->pluck('id');
        $appDrivers = $this->user->role('driver')->where('driver_type', 'app')->pluck('id');
        foreach ($period as $day) {
            $data[$day->format('Y-m-d')]["free_lancers"] =
                $this->driverReportData($day, $freeLancers, "freelancer", $status);
            $data[$day->format('Y-m-d')]["app_drivers"] = $this->driverReportData($day, $appDrivers, "app", $status);
            $data[$day->format('Y-m-d')]["stores"] = $this->storeReportData($day, $status);
            $totals = $this->addTotals($totals, $data[$day->format('Y-m-d')]);
        }
        $result["totals"] = $totals;
        $result["data"] = $data;
        return $result;
    }

    public function addTotals($totals, $day)
    {
        $totals["free_lancers_orders"] += $day["free_lancers"]["orders_count"];
        $totals["free_lancers_fee"] += $day["free_lancers"]["driver_fee"];
        $totals["app_drivers_orders"] += $day["app_drivers"]["orders_count"];
        $totals["app_drivers_fee"] += $day["app_drivers"]["driver_fee"];
        $totals["store_orders"] += $day["stores"]["orders_count"];
        $totals["store_fee"] += $day["stores"]["store_fee"];
        return $totals;
    }

    public function initTotals(): array
    {
        $totals = [];
        $totals["free_lancers_orders"] = 0;
        $totals["free_lancers_fee"] = 0;
        $totals["app_drivers_orders"] = 0;
        $totals["app_drivers_fee"] = 0;
        $totals["store_orders"] = 0;
        $totals["store_fee"] = 0;
        return $totals;
    }

    public function driverReportData($day, $drivers, $type, $status)
    {
        $data = [];
        $selects = array(
            'SUM(driver_fee) AS driver_fee',
            'COUNT(*) AS order_count',
            'SUM(store_fee) AS store_fee'
        );
        $query = $this->order;
        if ($status != false) {
            $query = $query->whereIn('status', $status);
        }
        $query = $query->whereDate('created_at', '=', $day->format('Y-m-d'))->selectRaw(implode(',', $selects))
            ->whereIn('driver_id', $drivers)->get();
        $data["active_drivers"] = $this->user->role('driver')
            ->where('driver_type', $type)->whereHas('driver', function ($query) use ($day) {
                $query->whereDate('created_at', '=', $day->format('Y-m-d'));
            })->count();
        $data["orders_count"] = $query[0]->order_count;
        $data["driver_fee"] = (int)$query[0]->driver_fee;
        return $data;
    }

    public function storeReportData($day, $status)
    {
        $data = [];
        $selects = array(
            'SUM(driver_fee) AS driver_fee',
            'COUNT(*) AS order_count',
            'SUM(store_fee) AS store_fee'
        );
        $query = $this->order;
        if ($status != false) {
            $query = $query->whereIn('status', $status);
        }
        $query = $query->whereDate('created_at', $day->format('Y-m-d'))->selectRaw(implode(',', $selects))->get();
        $data["active_stores"] = $this->store->whereHas('orders', function ($query) use ($day) {
            $query->whereDate('created_at', '=', $day->format('Y-m-d'));
        })->count();
        $data["orders_count"] = $query[0]->order_count;
        $data["store_fee"] = (int)$query[0]->store_fee;
        return $data;
    }
}
