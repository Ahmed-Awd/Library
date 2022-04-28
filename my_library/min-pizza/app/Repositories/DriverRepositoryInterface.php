<?php

namespace App\Repositories;

interface DriverRepositoryInterface
{


    public function get($search, $order);
    public function getByFilter($filter);
    public function show($driver);
    public function create($user, $data);
    public function update($driver, $data);
    public function delete($driver);
    public function changeStatus($driver);
    public function updateLocation($data);
    public function getAllAvl();
}
