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

class ReportAllDriversExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        
        return ['0'=>'ID',
                '1'=>' NAME',
                '2'=> 'PHONE',
                '3'=> 'TOTAL ORDERS PRICE',
                '4'=> 'TOTAL DELIVERY PRICE',
                '5'=> 'TOTAL ORDERS COUNT',
                '6' =>'TOTAL DELIVERY FEE',
                '7'=>'RATE'
            ];
    

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $k=1;
        $users=array();
        foreach($this->data as $dat)
        {
         $driver=array();
         $driver['id']=$k++;
         $driver['store']=$dat->name;
         $driver['phone']= $dat->country_code .''.$dat->phone;
         $driver['driver_sum_total_price']=$dat->driver_sum_total_price?$dat->driver_sum_total_price/100:'0';
         $driver['driver_sum_delivery_price']=$dat->driver_sum_delivery_price?$dat->driver_sum_delivery_price/100:'0';
         $driver['orders_count']=$dat->orders_count??'0';
         $driver['orders_sum_store_fee']=$dat->total_fee?$dat->total_fee/100:'0';
         $driver['avarage_rate']=$dat->avarage_rate??"Not Rated";
         $users[]=$driver;
        }
    return  collect($users);
    }
}
