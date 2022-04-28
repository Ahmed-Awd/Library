<?php

namespace App\Repositories;

use App\Models\Driver;
use App\Models\DriverNewOrders;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverRepository implements DriverRepositoryInterface
{

    private Driver $driver;


    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }


    public function get($search, $order)
    {
        $query =  $this->driver->join('users', 'users.id', '=', 'drivers.user_id')
            ->select('drivers.id', 'users.name', 'drivers.type', 'user_id', 'drivers.created_at', 'drivers.updated_at');
        if ($search != '') {
            $query = searchIt($query, ['type','name'], $search, true);
        }
        $query = $query->with('user');
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        }
        return $query->paginate(15);
    }

    public function getByFilter($filter)
    {
        $order = $filter['order'];
        unset($filter['order']);
        if (isset($filter['type'])) {
            if ($filter['type'] == "app") {
                $radius = Setting::getSettingByKey('max_radius_of_driver_in_order');
                $records = $this->driver->select('id', 'user_id', 'lat', 'lng')
                    ->where('type', 'app')->whereNotNull('lat')->whereNotNull('lng')->where('is_active', 1);
                $restaurant = $filter['restaurant'];
                $records = setNearest($records, $restaurant->lat, $restaurant->lng, $radius);
                $ids = $records->pluck('user_id');
                DB::table('driver_new_orders')->insert($this->driverOrders($ids, $order));
                return $records->with('user')->get();
            }
        } else {
            $drivers = $this->driver->where($filter)->with('user')->select('id', 'user_id', 'lat', 'lng');
            $ids = $drivers->pluck('user_id');
            DB::table('driver_new_orders')->insert($this->driverOrders($ids, $order));
            return $drivers->get();
        }
    }

    public function driverOrders($drivers, $order): array
    {
        $data = [];
        foreach ($drivers as $driver) {
            $temp['user_id'] = $driver;
            $temp['order_id'] = $order->id;
            $data[] = $temp;
        }
        return $data;
    }

    public function create($user, $data)
    {
        $user->assignRole('driver');
        $user->driver()->create($data);
    }

    public function show($driver)
    {
        return  $this->driver->where('id', $driver->id)->with('user')->first();
    }

    public function update($driver, $data)
    {
        $driver->update($data);
    }

    public function delete($driver)
    {
        $driver->delete();
    }


    public function changeStatus($driver): bool
    {
        $driver->is_active = !$driver->is_active;
        $driver->save();
        return $driver->is_active;
    }

    public function updateLocation($data)
    {
        $this->driver->where('user_id', Auth::user()->id)->update($data);
    }

    public function getAllAvl()
    {
        return $this->driver->where('is_active', 1)->select('id', 'user_id')->with('user:id,name')->get();
    }
}
