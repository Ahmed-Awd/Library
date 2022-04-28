<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['translated_value'];
    public static array $langs = ['en','sv'];

    public function getTranslatedValueAttribute()
    {
        $current = app()->getLocale();
        if (in_array($current, self::$langs)) {
            return $this->value->$current;
        }
        return $this->value->en;
    }

    public function getValueAttribute($value)
    {
        $value =   json_decode($value);
        return $value;
    }
}
