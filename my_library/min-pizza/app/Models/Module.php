<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
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

    public static function getModuleByKey($key)
    {
        $record = self::where('key', $key)->firstOrFail();
        return $record->value;
    }
}
