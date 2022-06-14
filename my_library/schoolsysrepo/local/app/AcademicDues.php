<?php

namespace App;

use App\Scopes\DeleteScope;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class AcademicDues extends Model
{
    protected $table='academics_dues';
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope());
    }
}
