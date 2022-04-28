<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Restaurant extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['owner:id,email,name,city_id','status:id,name','deliveryPrice','phones','paymentMethods'];
    protected $withCount = ['ratings'];
    protected $dates = ['closing_at'];
    protected $appends = ['rate'];

    public function getRateAttribute()
    {
        $value = $this->ratings()->avg('rate');
        return number_format((float)$value, 1, '.', '');
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function status()
    {
        return $this->belongsTo(RestaurantStatus::class);
    }

    public function workTime()
    {
        return $this->hasMany(Worktime::class);
    }
    public function holiday()
    {
        return $this->hasMany(Holiday::class);
    }
    public function takeaway()
    {
        return $this->workTime()->where('takeaway', 1);
    }

    public function delivery()
    {
        return $this->workTime()->where('delivery', 1);
    }

    public function optionCategory()
    {
        return $this->hasMany(OptionCategory::class);
    }

    public function optionTemplate()
    {
        return $this->hasMany(OptionTemplate::class);
    }

    public function deliveryPrice()
    {
        return $this->hasOne(DeliveryPriceOption::class);
    }

    public function setting()
    {
        return $this->hasOne(RestaurantSetting::class);
    }

    public function offer()
    {
        return $this->hasOne(RestaurantOffer::class);
    }

    public function currentOffer()
    {
        return $this->offer()->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"));
    }

    public function menu()
    {
        return $this->hasOne(Menu::class);
    }

    public function ratings()
    {
        return $this->hasMany(RestaurantRating::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function phones()
    {
        return $this->hasMany(RestaurantPhone::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'restaurant_categories', 'restaurant_id', 'category_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'restaurant_follow', 'restaurant_id', 'user_id');
    }

    public function deliveryDistances()
    {
        return $this->hasMany(RestaurantDeliveryDistance::class);
    }
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'restaurant_payment_methods', 'restaurant_id', 'method_id')
        ->withTimestamps();
    }
}
