<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worktime extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_id',
        'restaurant_id',
        'open_from',
        'open_to',
        'takeaway',
        'delivery',
        'status'
    ];
    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
