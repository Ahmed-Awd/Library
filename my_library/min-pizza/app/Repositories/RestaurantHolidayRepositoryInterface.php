<?php

namespace App\Repositories;

use App\Models\Restaurant;

interface RestaurantHolidayRepositoryInterface
{
    public function get($id);
    public function store($data);
    public function show($id);
    public function update($id, $data);
    public function delete($id);
}
