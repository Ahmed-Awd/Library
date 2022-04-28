<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefuseReason extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    public static array $langs = ['en','sv'];

    public function getReasonAttribute($value)
    {
        $current = app()->getLocale();
        $reason =   json_decode($value);
        if (in_array($current, self::$langs)) {
            return $reason->$current;
        }
        return $reason->en;
    }
}
