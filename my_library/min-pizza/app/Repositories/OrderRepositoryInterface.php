<?php

namespace App\Repositories;

interface OrderRepositoryInterface
{
    public function get($filter, $range = false);
    public function getStats($filter, $range);
    public function show($order);
    public function count($filter);
    public function showWereIn($order);
    public function store($data);
    public function update($order, $data);
    public function getCanceled($filter, $range);
    public function delete($order);
    public function rate($order, $data);
    public function report($filter, $range = false);
    public function sum($filter, $range = false);
    public function newOrders($restaurant);
    public function cancelOrder($order, $data);
    public function getAll($range, $type, $status, $restaurant, $from, $to);
    public function getAllCount();
    public function assignDriver($order, $data);
}
