<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class Religion extends Model
{

    protected $fillable=[
        'religion_name',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

}
