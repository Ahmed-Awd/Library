<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = true;
    protected $appends = ['order_price','customer_payed_for_delivery','store_profit','smart_income'];

    protected $hidden = [
        'qr_code',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'accepted_by_driver_at' => 'datetime',
        'order_delivered_at' => 'datetime',
        'order_taken_from_store_at' => 'datetime',
    ];

    public function getOrderPriceAttribute()
    {
        return $this->total_price + $this->delivery_price;
    }

    public function getSmartIncomeAttribute()
    {
        return $this->driver_fee + $this->store_fee;
    }

    public function getCustomerPayedForDeliveryAttribute()
    {
        return $this->delivery_price - $this->delivery_fee_payed_by_store;
    }

    public function getStoreProfitAttribute()
    {
        return $this->total_price - $this->delivery_fee_payed_by_store;
    }

    public function store()
    {
        return $this->belongsTo(Store::class)->withTrashed();
    }

    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'status');
    }
}
