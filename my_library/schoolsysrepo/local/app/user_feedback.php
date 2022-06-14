<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class user_feedback extends Model
{
    protected $table = "user_feedback";
    public $timestamps = false;

    public static function get_new_count(){
        return user_feedback::where('state','new')->where('user_id',Auth::user()->id)->count();
    }

    public static function viewed($id){
        $update['state'] = 'old';
        user_feedback::where('feedback_id',$id)->where('user_id',Auth::user()->id)->update($update);
    }
}
