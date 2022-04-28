<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'phone',
        'restaurant_id',
    ];
}
