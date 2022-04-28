<?php

namespace App\Repositories;

interface RestaurantSettingRepositoryInterface
{
    public function get($id);
    public function store($id, $data);
}
