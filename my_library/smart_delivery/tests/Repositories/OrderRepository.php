<?php

namespace Tests\Repositories;

use App\Repositories\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function get($filter, $orderBy, $range = false)
    {
    }

    public function count($filter)
    {
    }

    public function store($store, $inputData, $status, $deliveryPrice, $storePayed, $distance)
    {
    }

    public function rate($order, $data)
    {
    }

    public function delete($order)
    {
    }

    public function changeStatus($order, $status)
    {
    }

    public function history($range = false, $user = false)
    {
    }
}
