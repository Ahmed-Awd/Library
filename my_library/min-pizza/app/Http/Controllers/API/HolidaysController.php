<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantHolidayRequest;
use App\Models\Holiday;
use App\Models\Restaurant;
use App\Repositories\RestaurantHolidayRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class HolidaysController extends Controller
{
    private RestaurantHolidayRepositoryInterface $restaurantHolidayRepository;

    public function __construct(RestaurantHolidayRepositoryInterface $restaurantHolidayRepository)
    {
        $this->restaurantHolidayRepository = $restaurantHolidayRepository;
    }

    public function index(Restaurant $restaurant): JsonResponse
    {
        $holidays = $this->restaurantHolidayRepository->get($restaurant->id);
        return response()->json(['holidays' => $holidays], 200);
    }


    public function store(RestaurantHolidayRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->restaurantHolidayRepository->store($data);
        return response()->json(['message' => Lang::get('messages.create')]);
    }

    public function show(Holiday $holiday): JsonResponse
    {
        $holiday = $this->restaurantHolidayRepository->show($holiday->id);
        return response()->json(['data' => $holiday]);
    }


    public function update(RestaurantHolidayRequest $request, Holiday $holiday): JsonResponse
    {
        $data = $request->validated();
        $this->restaurantHolidayRepository->update($holiday->id, $data);
        return response()->json(['message' => Lang::get('messages.update')]);
    }


    public function destroy(Holiday $holiday): JsonResponse
    {
        $this->restaurantHolidayRepository->delete($holiday->id);
        return response()->json(["message" => Lang::get('messages.delete')]);
    }
}
