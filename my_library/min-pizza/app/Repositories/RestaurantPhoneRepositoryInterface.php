<?php

namespace App\Repositories;

interface RestaurantPhoneRepositoryInterface
{
    public function store($id, $data);
    public function delete($id);
    public function update($data, $restaurant);
}
