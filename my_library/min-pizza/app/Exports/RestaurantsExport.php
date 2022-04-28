<?php

namespace App\Exports;

use App\Models\Restaurant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class RestaurantsExport implements FromCollection, WithHeadings
{
    private $data;
    private $other;

    public function __construct(array $data, array $other)
    {
        $this->data = $data;
        $this->other = $other;
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
        if (($key = array_search('owner_name', $this->data)) !== false) {
            $this->data[$key] = 'users.name as owner_name';
        }
        if (($key = array_search('name', $this->data)) !== false) {
            $this->data[$key] = 'restaurants.name as name';
        }
        $restaurant = Restaurant::select($this->data);
        if (in_array('total_amount', $this->other)) {
            $restaurant = $restaurant->withSum('orders', 'total_amount');
        }
        if (in_array('orders_count', $this->other)) {
            $restaurant = $restaurant->withCount('orders');
        }
        if (in_array('rate', $this->other)) {
            $restaurant = $restaurant->withAvg('ratings', 'rate');
        }
        if (in_array('users.name as owner_name', $this->data)) {
            $restaurant = $restaurant->leftJoin('users', 'users.id', '=', 'restaurants.owner_id');
        }
        $restaurant = $restaurant->get();
        return $restaurant;
    }
}
