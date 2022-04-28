<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [];
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

    public function optionValues()
    {
        return $this->hasMany(OptionValue::class, 'option_category_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_option')->withPivot(['type']);
    }
}
