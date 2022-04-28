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

class ReportToAdminExport implements FromCollection, WithHeadings
{
    private $data;
    private $type;

    public function __construct( $data ,$type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function headings(): array
    {
        
        if($this->type=='store')
        return ['0'=>'ID',
                '1'=>' Order No',
                '2'=> 'Delivery Price',
                '3'=> 'Distance',
                '4'=> 'Total',
                '5'=> 'STORE FEE',
                '6' =>'Status',
                '7'=>'date'];
        else
        return ['0'=>'ID',
        '1'=>' Order No',
        '2'=> 'Delivery Price',
        '3'=> 'Total',
        '4'=> 'STORE FEE',
        '5'=> 'DELIVERY FEE',
        '6'=> 'DISTANCE',
        '7'=> 'RATE',
        '8' =>'Status',
        '9'=>'date'];

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    if($this->type=='store')
       $select= ['id',
        'order_number',
        'delivery_price',
        'distance_store_order',
        'total_price',
        'store_fee',
        'status',
        'created_at'];
        else
        $select= ['id',
        'order_number',
        'delivery_price',
        'total_price',
        'store_fee',
        'driver_fee',
        'distance_store_order',
        'rate',
        'status',
        'created_at'];

       $orders =Order::select($select)
       ->whereIn('id',$this->data)->get();
      $k=1;
       foreach($orders as $order)
       {
        $order->id=$k++;
        $order->total_price=$order->total_price?$order->total_price/100:'0';
        $order->store_fee=$order->store_fee?$order->store_fee/100:'0';
        if($this->type=='driver')
        {
        $order->driver_fee=$order->driver_fee?$order->driver_fee/100:'0';                
        $order->distance_store_order=$order->distance_store_order??'0'; 
        }               
        $order->status=$order->orderStatus->name;
        $order->created_at=$order->created_at->format('Y-m-d H:i:s');
       }
       $orders->makeHidden(['order_price','customer_payed_for_delivery','store_profit','smart_income','orderStatus'])  ;  
        return $orders;
    }
}
