<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportGeneralExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {

        return ['0' => 'ID',
                '1' => ' Order No',
                '2' => 'STORE',
                '3' => 'DRIVER',
                '4' => 'DRIVER PHONE',
                '5' => 'DELIVERY PRECENT',
                '6' => 'STORE FEE',
                '7' => 'DELIVERY PRICE',
                '8' => 'DISTANCE',
                '9' => 'DATE'
            ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $k = 1;
        $users = array();
        foreach ($this->data as $dat) {
            $driver = array();
            $driver['id'] = $k++;
            $driver['order_number'] = $dat->order_number;
            $driver['store'] = $dat->store ? $dat->store->name : '';
            $driver['driver'] = $dat->driver ? $dat->driver->name : '';
            $driver['driver_phone'] = $dat->driver ? $dat->driver->phone : '';
            $driver['delivery_fee_payed_by_store'] = $dat->delivery_fee_payed_by_store
                ? $dat->delivery_fee_payed_by_store / 100 : '0';
            $driver['store_profit'] = $dat->store_profit ? $dat->store_profit / 100 : '0';
            $driver['delivery_price'] = $dat->delivery_price ? $dat->delivery_price / 100 : '0';
            $driver['distance_store_order'] = $dat->distance_store_order ?? '0';
            $driver['date'] = $dat->created_at;
            $users[] = $driver;
        }
        return  collect($users);
    }
}
