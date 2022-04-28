<?php

namespace App\Repositories;

interface OrderItemRepositoryInterface
{
    public function get($order);
    public function show($orderItem);
    public function store($data);
    public function update($orderItem, $data);
}
