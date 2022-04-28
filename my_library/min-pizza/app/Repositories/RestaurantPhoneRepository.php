<?php

namespace App\Repositories;

use App\Models\RestaurantPhone;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\RestaurantPhoneRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RestaurantPhoneRepository implements RestaurantPhoneRepositoryInterface
{
    private RestaurantPhone $restaurantPhone;

    public function __construct(RestaurantPhone $restaurantPhone)
    {
        $this->restaurantPhone = $restaurantPhone;
    }


    public function store($id, $data)
    {
        $this->restaurantPhone->insertOrIgnore([
                "country_code" => $data["country_code"],
                "phone" => $data["phone"],
                "restaurant_id" => $id,
                "created_at" =>  Carbon::now(),
        ]);
    }

    public function delete($id)
    {
        $this->restaurantPhone->where('restaurant_id', $id)->delete();
    }

    public function update($data, $restaurant)
    {
        $restaurant->phones()->delete();
        $restaurant->phones()->createMany($data);
    }
}
