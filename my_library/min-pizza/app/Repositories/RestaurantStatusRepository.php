<?php

namespace App\Repositories;

use App\Models\RestaurantStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\RestaurantStatusRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RestaurantStatusRepository implements RestaurantStatusRepositoryInterface
{
    private RestaurantStatus $restaurantStatus;

    public function __construct(RestaurantStatus $restaurantStatus)
    {
        $this->restaurantStatus = $restaurantStatus;
    }
    public function get()
    {
        return $this->restaurantStatus->all();
    }
}
