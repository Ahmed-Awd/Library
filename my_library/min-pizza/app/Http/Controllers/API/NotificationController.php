<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralNotificationRequest;
use App\Http\Requests\SpecialNotificationRequest;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Repositories\GeneralNotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
    private GeneralNotificationRepositoryInterface $generalNotificationRepository;

    public function __construct(GeneralNotificationRepositoryInterface $generalNotificationRepository)
    {
        $this->generalNotificationRepository = $generalNotificationRepository;
    }

    public function sendGeneralNotification(GeneralNotificationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $to = [];
        $role = $data['role'];
        $current = $this->findRoleName($role);
        if ($current) {
            $users = User::role($current->name)->has('tokens')
                ->where('is_disabled', 0)->select('id', 'name', 'email')->get();
            array_push($to, ...$users->pluck('id'));
        }
        $data['type'] = 'general';
        $record = $this->generalNotificationRepository->store($data, $to);
        Notification::send($users, new GeneralNotification($data['subject'], $data['body'], $record));
        return response()->json(['message' => Lang::get('messages.notifications.sent')]);
    }

    public function sendSpecialNotification(SpecialNotificationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['type'] = 'special';
        $users = User::whereIn('id', $data['users'])->has('tokens')->select('id', 'name', 'email')->get();
        $to = $users->pluck('id');
        $data['users'] = json_encode($data['users']);
        $record = $this->generalNotificationRepository->store($data, $to);
        Notification::send($users, new GeneralNotification($data['subject'], $data['body'], $record));
        return response()->json(['message' => Lang::get('messages.notifications.sent')]);
    }

    public function get($type, Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $data = $this->generalNotificationRepository->get($type, $search, $order);
        return response()->json(['notifications' => $data]);
    }

    public function show(\App\Models\GeneralNotification $generalNotification): JsonResponse
    {
        $generalNotification = $this->generalNotificationRepository->show($generalNotification);
        return response()->json(['data' => $generalNotification]);
    }

    public function getMyNotifications(): JsonResponse
    {
        $user = Auth::user();
        $notifications = $this->generalNotificationRepository->getMyNotifications($user);
        return response()->json(['notifications' => $notifications]);
    }

    public function getMyNotification(\App\Models\GeneralNotification $generalNotification): JsonResponse
    {
        $notification = $this->generalNotificationRepository->getMyNotification($generalNotification);
        return response()->json(['notification' => $notification->makeHidden(['users', 'roles'])]);
    }

    public function newNotificationsCount(): JsonResponse
    {
        $user = Auth::user();
        $count = $this->generalNotificationRepository->newNotificationsCount($user);
        return response()->json(['count' => $count]);
    }

    public function findRoleName($role)
    {
        try {
            $current = Role::findById($role, 'web');
        } catch (\Exception $exception) {
            return false;
        }
        return $current;
    }
}
