<?php

namespace App\Repositories;

interface RestaurantOfferRepositoryInterface
{
    public function get($search, $order);
    public function show($restaurantOffer);
    public function store($data, $restaurant);
    public function delete($restaurantOffer);
}
