<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];
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
}
