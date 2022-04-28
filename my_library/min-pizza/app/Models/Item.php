<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['followers'];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
    public function optionTemplate()
    {
        return $this->belongsTo(OptionTemplate::class);
    }

    public function options()
    {
        return $this->belongsToMany(OptionCategory::class, 'item_option', 'item_id', 'option_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_favourites', 'item_id', 'user_id');
    }

    public function offer()
    {
        return $this->hasOne(ItemOffer::class);
    }

    public function currentOffer()
    {
        return $this->offer()->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"));
    }
}
