<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function get($search, $order, $role = 'customer');
    public function report($search, $role = 'customer');
    public function count($type = 'all', $role = 'customer');
    public function list($type);
    public function show($user);
    public function create($user);
    public function update($user, $data);
    public function delete($user);
    public function changeStatus($user, $hour = 0): bool;
    public function showFollows($user,$location=null);
    public function showFavourites($user);
    public function updateAddress($data, $user);
}
