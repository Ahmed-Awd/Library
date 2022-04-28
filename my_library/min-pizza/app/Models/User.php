<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;


    protected $fillable = [
        'name',
        'email',
        'password',
        'city_id',
        'country_code',
        'phone',
        'created_at',
        'reset_code',
        'profile_photo_path',
        'email_verified_at',
        'default_lang',
        'notifications',
        'is_disabled',
        "last_seen",
        "account_type",
        "facebook_id",
        "google_id",
        "apple_id",
    ];


    protected $hidden = [
        'password',
        'remember_token',
        "facebook_id",
        "google_id",
        "apple_id",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $dates = ['hold_to'];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(DefaultAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function followedRestaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_follow', 'user_id', 'restaurant_id');
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => [(string)$this->id]];
    }

    public function getNotificationToken()
    {
        return $this->tokens()->get()->pluck('notification_token');
    }
    public function favouriteItems()
    {
        return $this->belongsToMany(Item::class, 'user_favourites', 'user_id', 'item_id');
    }

    public function discountCodes()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function availableCodes()
    {
        return $this->discountCodes()
            ->where('usage_left', '!=', 0)
            ->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"));
    }

    public function myNotifications()
    {
        return $this->belongsToMany(GeneralNotification::class, 'user_notifications', 'user_id', 'notification_id')
            ->withPivot('new');
    }

    public function newNotificationsCount()
    {
        return $this->myNotifications()->where('new', 1)->count();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}
