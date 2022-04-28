<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'number_of_tries',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
