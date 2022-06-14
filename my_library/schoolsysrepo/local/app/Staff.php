<?php

namespace App;
use App\User;
use App\Staff;
use App\GeneralSettings as Setting;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Scopes\BranchScope;

class Staff extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope);
    }

    public function department()
    {
    	return $this->belongsTo('App\Department', 'department_id');
    }
    
    //
    public function prepareStaffID($userid)
    {
    	return (new Setting())->getStaffIDPrefix().$userid;
    }


}
