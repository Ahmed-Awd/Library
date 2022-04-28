<?php

namespace App\Repositories;

use App\Models\RestaurantOffer;
use Carbon\Carbon;

class RestaurantOfferRepository implements RestaurantOfferRepositoryInterface
{
    private RestaurantOffer $restaurantOffer;

    public function __construct(RestaurantOffer $restaurantOffer)
    {
        $this->restaurantOffer = $restaurantOffer;
    }

    public function get($search, $order)
    {
        $query =  $this->restaurantOffer;
        if ($search != '') {
            $query = $query->whereHas('restaurant', function ($query) use ($search) {
                return $query->where('name', 'LIKE', '%'.$search.'%');
            });
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        }
        return $query->with('restaurant:id,name,owner_id,rank,logo,image')->paginate(15);
    }

    public function show($restaurantOffer)
    {
        return $this->restaurantOffer->where('id', $restaurantOffer->id)
            ->with('restaurant:id,name,rank,logo,image')->first();
    }

    public function store($data, $restaurant)
    {
        $this->restaurantOffer->updateOrinsert(["restaurant_id" => $restaurant], $data);
    }

    public function delete($restaurantOffer)
    {
        $restaurantOffer->delete();
    }
}
