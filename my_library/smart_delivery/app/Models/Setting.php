<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'default',
    ];

    public static function social(): array
    {
        $data['facebook'] = self::where('key', 'facebook')->select('key', 'value')->first();
        $data['instagram'] = self::where('key', 'instagram')->select('key', 'value')->first();
        $data['twitter'] = self::where('key', 'twitter')->select('key', 'value')->first();
        $data['youtube'] = self::where('key', 'youtube')->select('key', 'value')->first();
        $data['linkedIn'] = self::where('key', 'linkedIn')->select('key', 'value')->first();
        $data['whats_app'] = self::where('key', 'whats_app')->select('key', 'value')->first();
        $data['email'] = self::where('key', 'email')->select('key', 'value')->first();
        return $data;
    }

    public static function whatsapp()
    {
        $data['whats'] = self::where('key', 'whats_app')->select('key', 'value')->first();
        $data['support_number'] = self::where('key', 'support_number')->select('key', 'value')->first();
        $data['driver_app_android_ver'] = self::where('key', 'driver_app_android_ver')->select('key', 'value')->first();
        $data['driver_app_IOS_ver'] = self::where('key', 'driver_app_IOS_ver')->select('key', 'value')->first();
        $data['owner_app_android_ver'] = self::where('key', 'owner_app_android_ver')->select('key', 'value')->first();
        $data['owner_app_IOS_ver'] = self::where('key', 'owner_app_IOS_ver')->select('key', 'value')->first();
        return $data;
    }

    public static function apps(): array
    {
        $data['app_store'] = self::where('key', 'app_store')->select('key', 'value')->first();
        $data['google_store'] = self::where('key', 'google_store')->select('key', 'value')->first();
        $data['huawei_app_gallery'] = self::where('key', 'huawei_app_gallery')->select('key', 'value')->first();
        return $data;
    }
}
