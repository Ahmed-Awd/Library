<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;

class Branch extends Model
{
    protected $table = "branch";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

}
