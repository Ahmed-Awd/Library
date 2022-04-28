<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCode extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $appends = ['package_price'];

    protected $guarded = [];
    protected $casts = [
        'purchased_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getPackagePriceAttribute()
    {
        return $this->package()->first()->price;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
