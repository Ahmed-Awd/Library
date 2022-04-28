<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function optionSecondary()
    {
        return $this->hasMany(OptionSecondary::class);
    }

    public function primaryOption()
    {
        return $this->belongsTo(OptionCategory::class, 'primary_option_id');
    }
}
