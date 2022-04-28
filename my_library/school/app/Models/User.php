<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\CategoryScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens,HasRoles,SoftDeletes, FillRecord;


    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
        static::addGlobalScope(new CategoryScope);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_number',
        'category_id',
        'language_id',
        'branch_id',
        'phone',
        'address',
        'username',
        'status',
        'nationality',
        'image',
        'is_suspended'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
