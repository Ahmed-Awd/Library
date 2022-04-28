<?php

namespace App\Repositories;

use App\Models\Restaurant;

interface RestaurantRepositoryInterface
{
    public function get($filters, $token, $location, $order);
    public function report($search = false);
    public function count($type = 'all');
    public function all();
    public function show(Restaurant $restaurant, $token,$location=null);
    public function delete($id);
    public function store($data);
    public function update($id, $data);
    public function updateStatus($id, $data);
    public function showFollowers();
    public function updateRank($restaurant, $rank);
    public function assignMethod($restaurant, $methods);
    public function unAssignMethod($restaurant, $method);
    public function getForAdmin($filters, $order);
}
