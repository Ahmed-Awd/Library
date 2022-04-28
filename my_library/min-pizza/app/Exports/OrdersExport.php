<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OrdersExport implements FromCollection, WithHeadings
{
    private $data;
    private $other;
    private $range;
    private $filters;

    public function __construct(
        array $data,
        array $other,
        array $filters,
        $range = false
    ) {
        $this->data = $data;
        $this->other = $other;
        $this->range = $range;
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return array_merge($this->data, $this->other);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $select = array();
        if (($key = array_search('delivery_address', $this->data)) !== false) {
            $this->data[$key] = 'orders.address as delivery_address';
        }
        if (($key = array_search('created_at', $this->data)) !== false) {
            $this->data[$key] = 'orders.created_at as created_at';
        }

        if (in_array('address', $this->other)) {
            $select[] = 'restaurants.address as orginal_address';
        }
        if (in_array('user', $this->other)) {
            $select[] = 'users.name as user';
        }
        if (in_array('restaurant', $this->other)) {
            $select[] = 'restaurants.name as restaurant';
        }
        if (in_array('driver', $this->other)) {
            $select[] = 'drivers.name as driver';
        }
        if (in_array('status', $this->other)) {
            $select[] = 'order_statuses.name as status';
        }
        if (in_array('code', $this->other)) {
            $select[] = 'discount_codes.code as code';
        }

        $orders =  Order::select(array_merge($this->data, $select));
        if (in_array('user', $this->other)) {
            $orders = $orders->join('users', 'users.id', '=', 'orders.user_id');
        }
        if (in_array('driver', $this->other)) {
            $orders = $orders->join('users as driver', 'driver.id', '=', 'orders.driver_id');
        }
        if (in_array('status', $this->other)) {
            $orders = $orders->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id');
        }
        if (in_array('restaurant', $this->other) || in_array('address', $this->other)) {
            $orders = $orders->join('restaurants', 'orders.restaurant_id', '=', 'restaurants.id');
        }
        if (in_array('code', $this->other)) {
            $orders = $orders->leftJoin('discount_codes', 'orders.discount_code_id', '=', 'discount_codes.id');
        }
        if ($this->range) {
            $orders = dateFilter($orders, $this->range, 'created_at');
        }
        return $orders->where($this->filters)->get();
    }
}
