<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    public static array $langs = ['en','sv'];

    public function getNameAttribute($value)
    {
        $current = app()->getLocale();
        $name =   json_decode($value);
        if (in_array($current, self::$langs)) {
            return $name->$current;
        }
        return $name->sv;
    }
    public function getDescriptionAttribute($value)
    {
        $current = app()->getLocale();
        $name =   json_decode($value);
        if (in_array($current, self::$langs)) {
            return $name->$current;
        }
        return $name->sv;
    }
    public function log()
    {
        return $this->belongsToMany(Order::class, 'log_orders');
    }
}
