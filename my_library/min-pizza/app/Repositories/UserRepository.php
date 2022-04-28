<?php

namespace App\Repositories;

use App\Models\DefaultAddress;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;

class UserRepository implements UserRepositoryInterface
{

    private User $user;
    private DefaultAddress $defaultAddress;


    public function __construct(User $user, DefaultAddress $defaultAddress)
    {
        $this->user = $user;
        $this->defaultAddress = $defaultAddress;
    }


    public function list($type)
    {
        $users = [];
        try {
            $users =  $type == "all" ? $this->user->select('id', 'name')->get()
                : $this->user->role($type)->select('id', 'name')->get();
        } catch (\PHPUnit\Util\Exception $exception) {
            return $users;
        }
        return $users;
    }


    public function get($search, $order, $role = 'customer')
    {
        $query =  $this->user->role($role);
        if ($search != '') {
            $query = searchIt($query, ['name','email'], $search);
        }
        $query =  $query->with('city:id,name,country_id', 'city.country:id,name');
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        }
        return $query->paginate(15);
    }

    public function report($search, $role = 'customer')
    {
        return $this->user->role($role)
        ->where('name', 'LIKE', '%' . $search . '%')
        ->withSum('orders', 'total_amount')
        ->withCount('orders')
        ->paginate(15);
    }

    public function count($type = 'all', $role = 'customer')
    {
        $user = $this->user->role($role);
        if ($type == 'has_order') {
            $user = $user->whereHas('orders', function ($q) {
                $q->where('is_created_by_admin', 0);
                $q->groupBy('id');
                $q->havingRaw('COUNT(*) >= 1');
            });
        } elseif ($type == 'has_more_order') {
            $user = $user->whereHas('orders', function ($q) {
                $q->where('is_created_by_admin', 0);
                $q->groupBy('id');
                $q->havingRaw('COUNT(*) > 1');
            });
        } elseif ($type == 'new') {
            $user = dateFilter($user, 'this-week', 'created_at');
        }
        return $user->count();
    }
    public function create($data)
    {
        $data['email_verified_at'] = Carbon::now()->timestamp;
        $data['password']          = Hash::make($data['password']);

        if (isset($data['profile_photo_path'])) {
            $data['profile_photo_path'] = imageStore($data['profile_photo_path']);
        }
        return  $this->user->create($data);
    }


    public function update($user, $data)
    {
        if (isset($data['profile_photo_path'])) {
            $data['profile_photo_path'] = imageStore($data['profile_photo_path']);
        }
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
    }



    public function delete($user)
    {
        $user->delete();
    }

    public function show($user)
    {
        $base = $this->user->where('id', $user->id)->with('city', 'city.country');

        if ($user->hasRole('admin')) {
            return $base->with('permissions:id,name')->get();
        }

        if ($user->hasRole('owner')) {
            return $base->with('restaurant')->first();
        }

        if ($user->hasRole('customer')) {
            return $base->with('addresses')->first();
        }

        if ($user->hasRole('driver')) {
            return $base->with('driver', 'driver.restaurant')->first();
        }
        return $base->first();
    }

    public function changeStatus($user, $hour = 0): bool
    {
        $user->is_disabled = !$user->is_disabled;
        $user->hold_to = $hour ? Carbon::now()->addHours($hour) : null;
        $user->save();
        return $user->is_disabled;
    }
    public function showFollows($user,$location=null)
    {
        $query = $user->followedRestaurants()->withAvg('ratings', 'rate');
        if(!is_null($location))
        {
            if ($location['lat'] != false && $location['lng'] != false) {
                $query->where('is_active', 1);
                $query = setNearestFollows($query, $location['lat'], $location['lng']);
            }
        }
        return $query->paginate(15);
    }

    public function showFavourites($user)
    {
        return $user->favouriteItems()
        ->with([
        'category:id,name,menu_id',
        'category.menu:id,restaurant_id',
        'category.menu.restaurant:id,name',
        ])->paginate(15);
    }

    public function updateAddress($data, $user)
    {
        $this->defaultAddress->updateOrinsert(["user_id" => $user], $data);
    }
}
