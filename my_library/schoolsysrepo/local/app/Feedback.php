<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;


class Feedback extends Model
{
    protected $table = 'feedbacks';
 

    public static function getRecordWithSlug($slug)
    {
        return Feedback::where('slug', '=', $slug)->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }
}
