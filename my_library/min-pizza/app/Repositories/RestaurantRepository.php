<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\User;
use App\Services\GeoDistance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\RestaurantRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\QueryBuilder\QueryBuilder;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    private Restaurant $restaurant;
    private Menu $menu;

    public function __construct(Restaurant $restaurant, Menu $menu)
    {
        $this->restaurant = $restaurant;
        $this->menu = $menu;
    }

    public function get($filters, $token, $location, $order)
    {
        $query = $this->restaurant->withAvg('ratings', 'rate')
            ->with('categories:id,name', 'currentOffer', 'deliveryDistances', 'paymentMethods:id,name');
        $query = $this->filters($query, $filters);
        if ($location['lat'] != false && $location['lng'] != false) {
            $query->where('is_active', 1);
            $distance = $filters['distance'] != false ? $filters['distance']
                : Setting::getSettingByKey('max_radius_of_restaurant');
            $query = setNearestRestaurants($query, $location['lat'], $location['lng'], $distance);
        } else {
            return $this->restaurant->where('id', 0)->paginate(15);
        }
        if ($order['by'] != false) {
            $restaurants = QueryBuilder::for($query)->orderBy($order['by'], $order['type'])->paginate(15);
        } else {
            $restaurants = QueryBuilder::for($query)
                ->orderBy('status_id', 'asc')
//                ->orderBy(DB::raw('-`rank`'), 'desc')
                ->orderBy('distance', 'asc')->paginate(15);
        }

        if ($token != null) {
            try {
                $user = PersonalAccessToken::findToken($token)->tokenable_id;
            } catch (\Exception $exception) {
                return $restaurants;
            }
            foreach ($restaurants as $restaurant) {
                $restaurant->is_followed = $this->isFollowed($user, $restaurant);
            }
        }
        foreach ($restaurants as $restaurant) {
            $restaurant->my_deleivery_time = "0";
        }

        return $restaurants;
    }

    public function solveLocation($user, $query)
    {
        if ($user->defaultAddress()->exists()) {
            $location = $user->defaultAddress;
            $distance = Setting::getSettingByKey('max_radius_of_restaurant');
            $result = setNearest($query, $location->lat, $location->lng, $distance);
            return $result;
        }
        return $query->where('city_id', $user->city_id);
    }

    public function calcTimeToMe($location, $restaurant)
    {
        $result = resolve(GeoDistance::class)->calculate(
            [$restaurant->lat, $restaurant->lng],
            [$location['lat'], $location['lng']]
        );
        return $result['duration'];
    }

    public function filters($query, $filters)
    {
        if ($filters['category'] != false && $filters['category'] != "false") {
            $category = $filters['category'];
            $query->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', '=', $category);
            });
        }
        if ($filters['free_delivery'] == "true") {
            $query->whereHas('deliveryPrice', function ($query) {
                $query->where('delivery_price_options.type', '=', "free");
            });
        }
        if ($filters['offers'] == "true") {
            $query->has('currentOffer');
        }
        if ($filters['rest_type'] == "takeaway") {
            $query->has('takeaway');
        }
        if ($filters['rest_type'] == "delivery") {
            $query->has('delivery');
        }
        if ($filters['rate'] != false && $filters['rate'] != "false") {
            $rate = $filters['rate'];
            $query->whereHas('ratings', function ($q) use ($rate) {
                $q->select('restaurant_id')
                    ->groupBy('restaurant_ratings.restaurant_id')
                    ->havingRaw('AVG(rate) >= ?', [$rate])
                    ->havingRaw('AVG(rate) <= ?', [$rate + 1]);
            });
        }
        if ($filters['name'] != false && $filters['name'] != "false") {
            $name = $filters['name'];
            $query->where(function ($res) use ($name) {
                $res->where('name', 'LIKE', '%' . $name . '%')
                    ->orWhere('company_number', 'LIKE', '%' . $name . '%');
            });
        }
        return $query;
    }

    public function report($search = false)
    {
        $restaurants = $this->restaurant;
        if ($search != false) {
            $restaurants = $restaurants->where('name', 'LIKE', '%' . $search . '%');
        }
        $restaurants =  $restaurants->withAvg('ratings', 'rate')->withCount('orders')->withSum('orders', 'total_amount')
            ->paginate(15);
        return $restaurants;
    }

    public function count($type = 'all')
    {
        $restaurant = $this->restaurant;
        if ($type == 'new') {
            $restaurant = dateFilter($restaurant, 'this-week', 'created_at');
        }
        return $restaurant->count();
    }

    public function all()
    {
        $restaurants = $this->restaurant->select('id', 'name')->get();
        return $restaurants;
    }

    public function show(Restaurant $restaurant, $token,$location=null)
    {
        $restaurant = $this->restaurant
            ->with('workTime', 'workTime.day:id,name', 'currentOffer', 'deliveryDistances', 'paymentMethods:id,name')
            ->withAvg('ratings', 'rate')->findOrFail($restaurant->id);
        
            if(!is_null($location))
        {
            if ($location['lat'] != false && $location['lng'] != false) 
            {
                $lat=$location['lat'] ;
                $lng=$location['lng'] ;
                $restaurant->distance = 6371 * acos(cos(deg2rad( $lat ))
                * cos(deg2rad($restaurant->lat)) * cos(deg2rad($restaurant->lng) - deg2rad($lng ))
                + sin(deg2rad($lat)) * sin(deg2rad($restaurant->lat)));
            }
        }
        $restaurant->can_rate = false;
        if ($token != null) {
            try {
                $user = PersonalAccessToken::findToken($token)->tokenable_id;
            } catch (\Exception $exception) {
                return $restaurant;
            }
            $restaurant->is_followed = $this->isFollowed($user, $restaurant);
            $restaurant->can_rate = $this->checkIfOrdered($restaurant, $user);
            $my_rate = $restaurant->ratings()->where('user_id', $user)->first();
            $restaurant->my_rate = $my_rate == null ? 0 : $my_rate;
        }
        return $restaurant;
    }

    public function checkIfOrdered($restaurant, $user)
    {
        $res = Order::where('restaurant_id', $restaurant->id)->where('user_id', $user)->count();
        if ($res > 0) {
            return true;
        }
        return false;
    }

    public function isFollowed($user, $restaurant): bool
    {
        $exists = $restaurant->followers->contains($user);
        if ($exists) {
            return true;
        }
        return false;
    }

    public function workTime($restaurant, $day, $hour)
    {
        $open = $restaurant->workTime()->where('day_id', '=', $day)
            ->where('open_from', '<', $hour)->where('open_to', '>', $hour)->first();
        if ($open) {
            return $restaurant->workTime()->where('day_id', '=', $day)
                ->where('open_from', '<', $hour)->where('open_to', '>', $hour)
                ->with('day:id,name')->select('id', 'day_id', 'open_to as next_time')->first();
        }
        $last_time = $restaurant->workTime()->where('day_id', '=', $day)->where('open_to', '<', $hour)
            ->select('id', 'day_id')->orderBy('id', 'desc')
            ->first();
        if ($last_time) {
            return $restaurant->workTime()->where('id', '>', $last_time->id)
                ->with('day:id,name')->select('id', 'day_id', 'open_from as next_time')->first();
        }
        return $restaurant->workTime()->where('day_id', '=', $day)
            ->with('day:id,name')->select('id', 'day_id', 'open_from as next_time')->first();
    }

    public function store($data)
    {
        $rest_id = null;
        //$password = Str::random(10);
        $password = $data['password'];
        DB::transaction(function () use ($data, &$rest_id, $password) {
            $id = DB::table('users')->insertGetId([
                "name" => $data["owner_name"],
                "email" => $data["owner_email"],
                "city_id" => $data["city_id"],
                "phone" => $data["phone"],
                "country_code" => $data["country_code"],
                "password" => bcrypt($password),
                "created_at" => Carbon::now(),
                "email_verified_at" => Carbon::now(),
            ]);
            $rest_id = DB::table('restaurants')->insertGetId([
                "name" => $data["restaurant_name"],
                "lng" => $data["lng"],
                "lat" => $data["lat"],
                "address" => $data["address"],
                "min_order_price" => $data["min_order_price"] ?? 0,
                "logo" => isset($data["logo"]) ? imageStore($data["logo"]) : 'min_pizza.png',
                "image" => isset($data["image"]) ? imageStore($data["image"]) : 'min_pizza.png',
                "company_name" => $data["company_name"] ?? null,
                "company_number" => $data["company_number"] ?? null,
                "city_id" => $data["city_id"],
                "ZIP_code" => $data["ZIP_code"] ?? null,
                "prepare_order_time" => $data["prepare_order_time"] ?? 5,
                "scheduling_order" => isset($data["scheduling_order"]) ? $data["scheduling_order"] : 0,
                "is_active" => $data["is_active"],
                "vat" => $data["vat"] ?? null,
                "status_id" => $data["status_id"],
                "owner_id" => $id,
                "created_at" => Carbon::now(),
            ]);

            $this->menu->create(["restaurant_id" => $rest_id]);
            $user = User::find($id);
            $user->assignRole('owner');
        });
        return $rest_id;
    }

    public function update($id, $data)
    {
        $record = $this->restaurant->findOrFail($id);

        if (isset($data["restaurant_name"])) {
            $data['name'] = $data["restaurant_name"];
            unset($data["restaurant_name"]);
        }
        if (isset($data["logo"])) {
            $data['logo'] = imageStore($data["logo"]);
        }
        if (isset($data["image"])) {
            $data['image'] = imageStore($data["image"]);
        }

        $record->update($data);
    }

    public function updateStatus($id, $data)
    {
        $record = $this->restaurant->findOrFail($id);
        $record->update([
            "status_id" => $data["status_id"],
            "closing_time" => isset($data['closing_time']) ? $data['closing_time'] : null,
            "closing_at" => Carbon::now()
        ]);
    }

    public function delete($id)
    {
        $this->restaurant->where('id', $id)->delete();
    }

    public function showFollowers()
    {
        $myFollows = $this->restaurant->followers;
        return $myFollows;
    }

    public function updateRank($restaurant, $rank)
    {
        $this->restaurant->where('id', $restaurant)->update(['rank' => $rank]);
    }

    public function assignMethod($restaurant, $methods)
    {
        $restaurant->paymentMethods()->detach();
        $restaurant->paymentMethods()->syncWithoutDetaching($methods);
    }

    public function unAssignMethod($restaurant, $method)
    {
        $restaurant->paymentMethods()->detach($method);
    }

    public function getForAdmin($filters, $order)
    {
        $query = $this->restaurant->withAvg('ratings', 'rate')
            ->with('categories:id,name', 'currentOffer', 'deliveryDistances', 'paymentMethods:id,name');
        $query = $this->filters($query, $filters);
        if ($order['by'] == "status") {
            $order['by'] = "status_id";
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $restaurants = QueryBuilder::for($query)->orderBy($order['by'], $order['type'])->paginate(15);
        } else {
            $restaurants = QueryBuilder::for($query)
                ->orderBy('id', 'asc')->paginate(15);
        }

        return $restaurants;
    }
}
