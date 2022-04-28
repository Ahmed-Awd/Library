<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;

    protected $guarded = [];
    public static array $languages = ['en','tr','ar'];
    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function getNameAttribute($value)
    {
        $current = app()->getLocale();
        $name =   json_decode($value);
        if (in_array($current, self::$languages)) {
            return $name->$current;
        }
        return $name->en;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
