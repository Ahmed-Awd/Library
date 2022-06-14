<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class HomeWork extends Model
{
    protected $table="home_works";
    public $timestamps = false;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }

}
