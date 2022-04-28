<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'country_code',
        'driver_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(Attachment::class, 'fileable');
    }
}
