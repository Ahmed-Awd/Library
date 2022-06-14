<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class LmsCategory extends Model
{
    protected $table = 'lmscategories';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

    public static function getRecordWithSlug($slug)
    {
        return LmsCategory::where('slug', '=', $slug)->first();
    }

    public function contents()
    {
        return $this->hasMany('App\LmsContent', 'category_id');
    }
}
