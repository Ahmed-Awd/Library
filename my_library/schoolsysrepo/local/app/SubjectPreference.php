<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class SubjectPreference extends Model
{
    protected $table = 'subjectpreferences';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }
}
