<?php

namespace App\Repositories;

interface GeneralNotificationRepositoryInterface
{
    public function store($data, $to);

    public function get($type, $search, $order);

    public function show($generalNotification);

    public function delete($generalNotification);

    public function getMyNotifications($user);
    public function newNotificationsCount($user);

    public function getMyNotification($generalNotification);
}
