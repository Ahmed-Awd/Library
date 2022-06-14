<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class Category extends Model
{

    protected $table = 'categories';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

}
