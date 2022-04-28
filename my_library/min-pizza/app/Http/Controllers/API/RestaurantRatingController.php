<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantRatingRequest;
use App\Models\Restaurant;
use App\Models\RestaurantRating;
use App\Repositories\RestaurantRatingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class RestaurantRatingController extends Controller
{

    private RestaurantRatingRepositoryInterface $restaurantRatingRepository;

    public function __construct(
        RestaurantRatingRepositoryInterface $restaurantRatingRepository,
    ) {
        $this->restaurantRatingRepository = $restaurantRatingRepository;
    }

    public function index(Restaurant $restaurant): JsonResponse
    {
        $ratings = $this->restaurantRatingRepository->get($restaurant);
        return response()->json(['ratings' => $ratings]);
    }


    public function store(StoreRestaurantRatingRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->restaurantRatingRepository->store($restaurant, $validated);
        if ($result == false) {
            return response()->json(["message" => Lang::get('messages.restaurant_rating.order_first')], 200);
        }
        return response()->json(["message" => Lang::get('messages.restaurant_rating.update')], 200);
    }

    public function already(Restaurant $restaurant)
    {
        $user = Auth::user();
        $result = $this->restaurantRatingRepository->checkIfRated($restaurant, $user);
        if ($result == false) {
            return response()->json(["result" => false], 200);
        }
        return response()->json(["result" => true,"record" => $result], 200);
    }


    public function show(RestaurantRating $restaurantRating): JsonResponse
    {
        $rating = $this->restaurantRatingRepository->show($restaurantRating);
        return response()->json(['rating' => $rating]);
    }


    public function destroy(RestaurantRating $restaurantRating): JsonResponse
    {
        $this->restaurantRatingRepository->delete($restaurantRating);
        return response()->json(["message" => Lang::get('messages.restaurant_rating.delete')], 200);
    }
}
