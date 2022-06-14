<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

use App\Scopes\DeleteScope;

class Author extends Model
{
   protected $table="authors";
    
    public static function getRecordWithSlug($slug)
    {
        return Author::where('slug', '=', $slug)->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }
}
