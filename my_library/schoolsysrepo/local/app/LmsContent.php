<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class LmsContent extends Model
{
    protected $table = 'lmscontents';


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }
     

    public static function getRecordWithSlug($slug)
    {
        return LmsContent::where('slug', '=', $slug)->first();
    }

    public function category()
    {
        return $this->belongsTo('App\Lmscategory', 'category_id');
    }
}
