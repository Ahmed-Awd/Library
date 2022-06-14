<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class UsersLogin extends Model
{
    protected $table = "users_login";
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope);
    }

}
