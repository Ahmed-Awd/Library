<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
