<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [];
    protected $dates = ['scheduling_to'];
    protected $casts = ['paid' => 'boolean'];

    public function getAddressAttribute($value)
    {
        return json_decode($value);
    }
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reason()
    {
        return $this->belongsTo(RefuseReason::class, 'refuse_reason_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class)->where('type', 'paid');
    }
    public function refund()
    {
        return $this->hasOne(Payment::class)->where('type', 'refund');
    }
    public function logStatus()
    {
        return $this->belongsToMany(OrderStatus::class, 'log_orders', 'order_id', 'order_status_id')
        ->withTimestamps();
    }
    public function scopePaid($query)
    {
        return $query->where('paid', 1);
    }
}
