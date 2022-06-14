<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class user_notifications extends Model
{
    protected $table = "user_notification";
    public $timestamps = false;

    public static function get_new_count(){
        $date = date('Y-m-d');
        $count = Notification::join('user_notification','notifications.id','=','user_notification.notification_id')
            ->where('user_notification.user_id',Auth::user()->id)
            ->where('notifications.valid_from', '<=', $date)
            ->where('notifications.valid_to', '>=', $date)
            ->where('user_notification.state','new')
            ->count();
       // return user_notifications::where('state','new')->where('user_id',Auth::user()->id)->count();
        return $count;
    }

    public static function viewed($id){
        $update['state'] = 'old';
        user_notifications::where('notification_id',$id)->where('user_id',Auth::user()->id)->update($update);
    }
}
