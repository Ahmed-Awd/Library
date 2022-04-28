<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'balance',
        'reserved_balance',
        'is_disabled',
        'is_available',
        'last_fail_charge',
        'charge_fail_attempts',
        'profile_photo_url',
        'phone',
        'country_code',
        'mobile_verified_at',
        'reset_code',
        'last_activity',
        'activation_code',
        'activation_code_expire',
        'language',
        'town_id',
        'lat',
        'lng',
        'has_new_papers',
        'position_updated_at',
        'new_phone',
        'new_country_code',
        'new_phone_code',
        'number_of_tries',
        'driver_type',
        'online_time_today',
        'last_time_online'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'updated_at',
        'deleted_at',
        'position_updated_at',
        'new_phone_code',
        "activation_code",
        "activation_code_expire"
    ];

    protected $with = ['store'];
    protected $dates = ['created_at', 'updated_at', 'last_activity', 'activation_code_expire', 'last_fail_charge'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function store()
    {
        return $this->hasOne(Store::class, 'owner_id');
    }

    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }

    public function code()
    {
        return $this->hasOne(UserAuthCode::class);
    }

    public function newPapers()
    {
        return $this->hasOne(DriverNewPapers::class);
    }

    public function driver()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function currentOrder()
    {
        return $this->driver()->whereIn('status', [2,3]);
    }


    public function logs()
    {
        return $this->hasMany(DriverLog::class);
    }

    public function package()
    {
        return $this->hasMany(PackageCode::class);
    }

    public function createToken(string $name, array $abilities = ['*'], $notification_token = null)
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            "notification_token" => $notification_token,
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => [(string)$this->id]];
    }

    public function getNotificationToken()
    {
        return $this->tokens()->get()->pluck('notification_token');
    }

    public function files()
    {
        return $this->morphMany(Attachment::class, 'fileable');
    }
}
