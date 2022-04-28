<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function get($filter, $orderBy, $range = false, $report = false, $withoutPagination = false);
    public function getAll($filter, $orderBy, $range = false, $report = false);
    public function count($filter);
    public function store($store, $inputData, $status, $deliveryPrice, $storePayed, $distance, $storeFee);
    public function reorder($store, $order, $status, $deliveryPrice, $storePayed, $storeFee);
    public function rate($order, $data);
    public function delete($order);
    public function changeStatus($order, $status);
    public function update($order, $data);
    public function driverNewOrders($filter, $orderBy);
    public function history($range = false, $user = false);
    public function getTotalOutgoing($range = false, $user = false);
    public function getTotalFee($filter, $range = false);
    public function archive($of, $type = "all", $id = false);
    public function show($order);
}
