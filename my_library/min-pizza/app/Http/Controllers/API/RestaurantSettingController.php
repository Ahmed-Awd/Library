<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantSettingRequest;
use App\Models\Restaurant;
use App\Repositories\RestaurantSettingRepositoryInterface;
use App\Repositories\WorktimeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class RestaurantSettingController extends Controller
{
    //
    private RestaurantSettingRepositoryInterface $restaurantSettingRepository;
    public function __construct(
        RestaurantSettingRepositoryInterface $restaurantSettingRepository
    ) {
        $this->restaurantSettingRepository = $restaurantSettingRepository;
    }

    public function store(StoreRestaurantSettingRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->restaurantSettingRepository->store($restaurant->id, $validated);
        return response()->json(["message" => Lang::get('messages.restaurant_settings.update')], 200);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        $setting = $this->restaurantSettingRepository->get($restaurant->id);
        return response()->json(['setting' => $setting], 200);
    }
}
