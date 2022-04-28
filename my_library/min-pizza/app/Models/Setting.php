<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
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

    public function getContacts()
    {
        return $this->whereIn('key', ['contact_phone','contact_email','facebook','twitter','instagram'])->get();
    }

    public function getMobileVersions()
    {
        return $this->whereIn('key', ['customer_app_android_version','customer_app_IOS_version'
            ,'driver_app_android_version','driver_app_IOS_version','restaurant_app_android_version'
            ,'restaurant_app_IOS_version'])->get();
    }


    public static function getSettingByKey($key)
    {
        $record = self::where('key', $key)->firstOrFail();
        return $record->value;
    }
}
