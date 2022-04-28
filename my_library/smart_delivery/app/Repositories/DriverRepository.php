<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\Attachment;
use App\Models\DriverNewPapers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DriverRepository implements DriverRepositoryInterface
{

    private User $user;
    private Attachment $attachment;

    public function __construct(User $user, Attachment $attachment)
    {
        $this->user = $user;
        $this->attachment = $attachment;
    }

    public function get($withPagination = false, $searchParam = "")
    {
        $drivers = $this->user;
        if ($searchParam != "") {
            $drivers = searchIt($drivers, ["name","phone"], $searchParam, false);
        }
        $drivers = $drivers->role('driver')->with('town:id,name')->orderBy('last_activity', 'desc');
        if ($withPagination == true) {
            return $drivers->paginate(15);
        }
        return $drivers->get();
    }

    public function availableDrivers($town, $available = 1)
    {
        $drivers = $this->user->role('driver')->has('tokens')
            ->select(
                'id',
                'name',
                'town_id',
                'is_available',
                'country_code',
                'phone',
                'lat',
                'lng',
                'profile_photo_path',
                'driver_type'
            )
            ->where('is_available', $available)->where('town_id', $town)
            ->with('town:id,name', 'currentOrder')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();
        return $drivers;
    }

    public function show($user)
    {
        return $this->user->where('id', $user->id)->with('files', 'town')->first();
    }

    public function store($data)
    {
        $password_generate = Str::random(6);
        $data['password'] = Hash::make($password_generate);
        $current = $this->user->create($data);
        $current->assignRole('driver');
    }

    public function update($user, $driver)
    {
        $data['name'] = $driver->name;
        $data['username'] = $driver->username;
        $data['email'] = $driver->email;
        $data['phone'] = $driver->phone;
        $data['country_code'] = $driver->country_code;
        $user->update($data);
    }

    public function disabled($user)
    {
        $user->update(['is_disabled' => !$user->is_disabled]);
    }

    public function delete($user)
    {
        $user->delete();
    }

    public function changeAvailability()
    {
        $user = Auth::user();
        $user->is_available = !$user->is_available;
        $user->save();
        return $user->is_available;
    }


    public function updateLocation($data)
    {
        $data['position_updated_at'] = Carbon::now();
        Auth::user()->update($data);
    }

    public function updateMyInfo(User $user, $data)
    {
        $user->update($data);
    }


    public function updatePapers(User $user, $data)
    {
        if ($user->newPapers != null) {
            $user->newPapers->update($data);
        } else {
            $data['user_id'] = $user->id;
            $user->newPapers()->insert($data);
        }
        $user->update(["has_new_papers" => true]);
    }

    public function forceUpdatePapers(User $user, $data)
    {
        foreach ($data as $key => $value) {
            if ($value == null) {
                continue;
            }
            $this->attachment
                ->where('fileable_id', $user->id)
                ->where('fileable_type', $user::class)
                ->where('description', $key)
                ->update(["path" => $value]);
        }
    }

    public function cleanPapers(User $user)
    {
        $user->newPapers()->update(['personal_photo' => null,
            "license_photo" => null,
            "vehicle_photo" => null,
            "vehicle_license_photo" => null]);
    }

    public function freeDrivers()
    {
        $drivers = $this->user->role('driver')->has('tokens')->select('id', 'name', 'is_available', 'lat', 'lng')
            ->where('is_available', 1)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();
        return $drivers;
    }
}
