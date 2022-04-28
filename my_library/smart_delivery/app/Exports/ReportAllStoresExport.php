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

class ReportAllStoresExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        
        return ['0'=>'ID',
                '1'=>' STORE',
                '2'=> 'OWNER',
                '3'=> 'MAIL',
                '4'=> 'ORDER FEE PERCENTAGE	',
                '5'=> 'TOTAL ORDERS PRICE',
                '6'=> 'TOTAL DELIVERY PRICE',
                '7' =>'TOTAL ORDERS COUNT',
                '8'=>'TOTAL FEE'
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
            info($dat);
         $store=array();
         $store['id']=$k++;
         $store['store']=$dat->name;
         $store['owner']=$dat->owner->name;
         $store['email']=$dat->owner->email;
         $store['taken_from_percentage']=$dat->taken_from_percentage.'%';
         $store['orders_sum_total_price']=$dat->orders_sum_total_price?$dat->orders_sum_total_price/100:'0';
         $store['orders_sum_delivery_price']=$dat->orders_sum_delivery_price?$dat->orders_sum_delivery_price/100:'0';
         $store['orders_count']=$dat->orders_count??'0';
         $store['total']=$dat->orders_sum_store_fee?$dat->orders_sum_store_fee/100:'0';
         $users[]=$store;
        }
    return  collect($users);
    }
}
