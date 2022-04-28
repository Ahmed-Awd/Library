<?php

namespace App\Repositories;

interface RestaurantRatingRepositoryInterface
{
    public function get($filter);
    public function store($restaurant, $data);
    public function show($restaurantRating);
    public function delete($restaurantRating);
    public function checkIfRated($restaurant, $user);

}
