<?php

namespace App\Repositories;

use App\Models\RestaurantSetting;
use App\Repositories\RestaurantSettingRepositoryInterface;
use Carbon\Carbon;

class RestaurantSettingRepository implements RestaurantSettingRepositoryInterface
{
    private RestaurantSetting $restaurantSetting;

    public function __construct(RestaurantSetting $restaurantSetting)
    {
        $this->restaurantSetting = $restaurantSetting;
    }
    public function get($id)
    {
        $settings = [];
        $restaurantSetting=$this->restaurantSetting->where('restaurant_id', $id)->first();

        if (is_null($restaurantSetting)) {
            $restaurantSetting= $this->restaurantSetting->create(
                [
                    "restaurant_id" => $id,
                    "taken_percentage_from_delivery" => 0,
                    "taken_percentage_from_takeaway" =>0,
                    "max_delivery_distance" => 10,
                ]
            );
        }
        $settings[] = $restaurantSetting;
        return $settings;
    }

    public function store($id, $data)
    {
        $this->restaurantSetting->updateOrinsert(
            [
                "restaurant_id" => $id,
            ],
            [
                "taken_percentage_from_delivery" => $data["taken_percentage_from_delivery"],
                "taken_percentage_from_takeaway" => $data["taken_percentage_from_takeaway"],
                "max_delivery_distance" => $data["max_delivery_distance"],
                "restaurant_id" => $id,
                "created_at" =>  Carbon::now(),
            ]
        );
    }
}
