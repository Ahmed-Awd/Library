<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;
class Notification extends Model
{
     protected $table= "notifications";


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }


    public static function getRecordWithSlug($slug)
    {
        return Notification::where('slug', '=', $slug)->first();
    }

    public static function latestNotifications($limit = 5)
    {
    	
        

        $records = Notification:: where('valid_to','=>',date('Y-m-d'))
                              ->limit($limit)
                              ->orderBy('valid_to','desc');
        return $records->get();
    }

}
