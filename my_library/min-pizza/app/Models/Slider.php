<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    public static array $langs = ['en','sv'];

    public function getTitleAttribute($value)
    {
        $current = app()->getLocale();
        $title =   json_decode($value);
        if (in_array($current, self::$langs)) {
            return $title->$current;
        }
        return $title->en;
    }

    public function getContentAttribute($value)
    {
        $current = app()->getLocale();
        $content =   json_decode($value);
        if (in_array($current, self::$langs)) {
            return $content->$current;
        }
        return $content->en;
    }
}
