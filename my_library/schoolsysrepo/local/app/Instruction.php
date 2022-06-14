<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class Instruction extends Model
{
    protected $table="instructions";
   

    public static function getRecordWithSlug($slug)
    {
        return Instruction::where('slug', '=', $slug)->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }
}
