<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSecondary extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['secondaryOptionValue'];

    public function secondaryOption()
    {
        return $this->belongsTo(
            OptionCategory::class,
            'secondary_option_id'
        );
    }
    public function primaryOptionValue()
    {
        return $this->belongsTo(
            OptionValue::class,
            'primary_option_value_id'
        );
    }
    public function secondaryOptionValue()
    {
        return $this->belongsTo(
            OptionValue::class,
            'secondary_option_value_id'
        );
    }

    public function optionTemplate()
    {
        return $this->belongsTo(OptionTemplate::class);
    }
}
