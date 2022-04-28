<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ["translated_name"];

    public static array $langs = ['en','ar','tr'];

    public function getTranslatedNameAttribute()
    {
        $current = app()->getLocale();
        $name =   json_decode($this->value);
        if (in_array($current, self::$langs)) {
            return $name->$current;
        }
        return $name->en;
    }
}
