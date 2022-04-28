<?php


namespace App\Repositories;


use App\Models\RestaurantDeliveryDistance;

class RestaurantDistanceRepository implements RestaurantDistanceRepositoryInterface
{
    private RestaurantDeliveryDistance $deliveryDistance;

    public function __construct(RestaurantDeliveryDistance $deliveryDistance)
    {
        $this->deliveryDistance = $deliveryDistance;
    }

    public function update($data, $restaurant)
    {
        $restaurant->deliveryDistances()->delete();
        $restaurant->deliveryDistances()->createMany($data);
    }
}
