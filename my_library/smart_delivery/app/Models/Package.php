<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name',
        'value',
        'price',
        'type'
    ];

    public function code()
    {
        return $this->hasMany(PackageCode::class);
    }
}
