<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $with = [];

    public function getItemAttribute($value)
    {
        return json_decode($value);
    }
    public function getTaxAttribute($value)
    {
        return json_decode($value);
    }
    public function getTemplateSecondaryOptionsAttribute($value)
    {
        return json_decode($value);
    }
    public function getTemplateSecondaryOptionsQuantityAttribute($value)
    {
        return json_decode($value);
    }
    public function getTemplateSelectedOptionsAttribute($value)
    {
        return json_decode($value);
    }
    public function getSelectedOptionsAttribute($value)
    {
        return json_decode($value);
    }
    public function oder()
    {
        return $this->belongsTo(Order::class);
    }
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
    public function primaryOptionValue()
    {
        return $this->belongsTo(OptionValue::class, 'primary_option_value_id');
    }
}
