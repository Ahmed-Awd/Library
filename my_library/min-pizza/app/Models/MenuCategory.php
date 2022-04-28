<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MenuCategory extends Model
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

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function allItems()
    {
        return $this->hasMany(Item::class, 'category_id');
    }

    public function items()
    {
        return $this->allItems()->where('is_available', 1)->orderBy(DB::raw('-`rank`'), 'desc');
    }
}
