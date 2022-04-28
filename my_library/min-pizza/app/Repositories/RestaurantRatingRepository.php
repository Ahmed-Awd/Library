<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\RestaurantRating;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RestaurantRatingRepository implements RestaurantRatingRepositoryInterface
{
    private RestaurantRating $restaurantRating;
    private Order $order;

    public function __construct(RestaurantRating $restaurantRating, Order $order)
    {
        $this->restaurantRating = $restaurantRating;
        $this->order = $order;
    }
    public function get($restaurant)
    {
        return $this->restaurantRating->where('restaurant_id', $restaurant->id)
            ->with(['user:id,name,profile_photo_path','restaurant'])
            ->paginate(15);
    }

    public function store($restaurant, $data)
    {
        if ($this->checkIfOrdered($restaurant, Auth::user())) {
            $this->restaurantRating->updateOrinsert(
                [
                    "restaurant_id" => $restaurant->id,
                    "user_id" => auth()->user()->id,
                ],
                [
                    "restaurant_id" => $restaurant->id,
                    "user_id" => auth()->user()->id,
                    "comment" => $data["comment"],
                    "rate" => $data["rate"],
                    "created_at" =>  Carbon::now(),
                ]
            );
            return true;
        }
        return false;
    }

    public function show($restaurantRating)
    {
        return $this->restaurantRating->where('restaurant_id', $restaurantRating->id)
            ->with('user:id,name,profile_photo_path')->first();
    }

    public function delete($restaurantRating)
    {
        $restaurantRating->delete();
    }

    public function checkIfRated($restaurant, $user)
    {
        $res = $this->restaurantRating->where('restaurant_id', $restaurant->id)->where('user_id', $user->id)->first();
        if ($res) {
            return $res;
        }
        return false;
    }

    public function checkIfOrdered($restaurant, $user)
    {
        $res = $this->order->where('restaurant_id', $restaurant->id)->where('user_id', $user->id)->count();
        if ($res > 0) {
            return true;
        }
        return false;
    }
}
