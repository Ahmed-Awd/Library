<?php

namespace App\Repositories;

use App\Models\RestaurantCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\RestaurantCategoryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RestaurantCategoryRepository implements RestaurantCategoryRepositoryInterface
{
    private RestaurantCategory $restaurantCategory;

    public function __construct(RestaurantCategory $restaurantCategory)
    {
        $this->restaurantCategory = $restaurantCategory;
    }


    public function store($id, $category)
    {
        $this->restaurantCategory->updateOrinsert(
            [
                "restaurant_id" => $id,
                "category_id" => $category,
            ],
            [
            "category_id" => $category,
            "restaurant_id" => $id,
            "created_at" => Carbon::now(),
            ]
        );
    }

    public function delete($id)
    {
        $this->restaurantCategory->where('restaurant_id', $id)->delete();
    }
}
