<?php

namespace App\Repositories;

use App\Models\RestaurantFollow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RestaurantFollowRepository implements RestaurantFollowRepositoryInterface
{
    public function switch($restaurant)
    {
        $exist = $restaurant->followers()->where('user_id', Auth::user()->id)->first();
        $user = Auth::user()->id;
        isset($exist) ? $restaurant->followers()->detach($user) : $restaurant->followers()->attach($user);
        if ($exist) {
            return false;
        } else {
            return true;
        }
    }
}
