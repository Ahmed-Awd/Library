<?php

namespace App\Repositories;

interface DeliveryPriceOptionRepositoryInterface
{
    public function store($id, $data);
    public function delete($id);
}
