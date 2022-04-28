<?php

namespace App\Repositories;

use App\Models\GeneralNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GeneralNotificationRepository implements GeneralNotificationRepositoryInterface
{
    private GeneralNotification $generalNotification;

    public function __construct(GeneralNotification $generalNotification)
    {
        $this->generalNotification = $generalNotification;
    }

    public function store($data, $to)
    {
        $data['created_by'] = Auth::user()->id;
        $notification = $this->generalNotification->create($data);
        $notification->users()->attach($to);
        return $notification;
    }

    public function get($type, $search, $order)
    {
        $query = $this->generalNotification->where('type', $type);
        if ($search != '') {
            $query = searchIt($query, ['subject','body'], $search);
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }
        return $query->paginate(15);
    }

    public function show($generalNotification)
    {
        $notification =  $this->generalNotification->where('id', $generalNotification->id)
            ->with('creator:id,name')->first();
        if ($notification->users != null) {
            $notification->users = User::whereIn('id', json_decode($notification->users))->select('id', 'name')->get();
        }
        if ($notification->roles != null) {
            $notification->roles = Role::whereIn('id', json_decode($notification->roles))->select('id', 'name')->get();
        }
        return $notification;
    }

    public function delete($generalNotification)
    {
        $generalNotification->delete();
    }

    public function getMyNotifications($user)
    {
        return $user->myNotifications()->orderBy('created_at', 'desc')->paginate(15);
    }

    public function newNotificationsCount($user)
    {
        return $user->newNotificationsCount();
    }

    public function getMyNotification($generalNotification)
    {
        DB::table('user_notifications')->where('notification_id', $generalNotification->id)
            ->where('user_id', Auth::user()->id)->update(["new" => false]);
        return $generalNotification;
    }
}
