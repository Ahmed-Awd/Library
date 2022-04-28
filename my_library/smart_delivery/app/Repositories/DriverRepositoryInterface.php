<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 4:45 PM
 */

namespace App\Repositories;

use App\Models\User;

interface DriverRepositoryInterface
{
    public function get($withPagination = false, $searchParam = "");
    public function show($user);
    public function store($data);
    public function update($user, $data);
    public function disabled($user);
    public function delete($user);
    public function changeAvailability();
    public function updateLocation($data);
    public function updateMyInfo(User $user, $data);
    public function updatePapers(User $user, $data);
    public function forceUpdatePapers(User $user, $data);
    public function cleanPapers(User $user);
    public function availableDrivers($town, $available = 1);
    public function freeDrivers();
}
