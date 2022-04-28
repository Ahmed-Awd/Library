<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RestaurantFollow;
use App\Models\Restaurant;
use App\Repositories\RestaurantFollowRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class RestaurantFollowController extends Controller
{
    private RestaurantFollowRepositoryInterface $restaurantFollowRepository;
    public function __construct(RestaurantFollowRepositoryInterface $restaurantFollowRepository)
    {
        $this->restaurantFollowRepository = $restaurantFollowRepository;
    }


    public function followOrUnfollow(Restaurant $restaurant): JsonResponse
    {
        $status = $this->restaurantFollowRepository->switch($restaurant);
        return response()->json(["message" => Lang::get('messages.restaurant_follow.update'),"is_followed" => $status]);
    }
}
