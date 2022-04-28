<?php

namespace App\Repositories;

use App\Jobs\SendSms;
use App\Models\Store;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreRepository implements StoreRepositoryInterface
{
    private Store $store;


    public function __construct(Store $store)
    {
        $this->store = $store;
    }


    public function get($filter = false)
    {
        $store=$this->store->with('owner')->with('type', 'setting', 'town:id,name');
        if($filter)
        $store=$store->where('name','like','%'.$filter.'%');
        return $store->paginate(15);
    }

    public function all()
    {
        return $this->store->select('id', 'name', 'owner_id')->with('owner:id,name')->get();
    }

    public function show($store)
    {
        return $store->load(['owner', 'type','setting','town']);
    }

    public function saveSettings($store, $data)
    {
        $store->setting->update($data);
    }

    public function store($data)
    {
        $password = $data['password'] ?? '87654321';
        $mobile_verified_at = isset($data['password']) ? null : Carbon::now();
        $code['value'] = rand(1111, 9999);
        $code['time'] = Carbon::now()->addMinutes(settings('activation_code_expire'));
        $phone['country_code'] = $data['country_code'] ?? null;
        $phone['phone'] = $data['phone'] ?? null;
        $user = DB::transaction(function () use ($data, $password, $mobile_verified_at, $code, $phone) {
            $id = DB::table('users')->insertGetId([
                "name" => $data["owner_name"],
                "username" => $data["owner_username"],
                "email" => $data["owner_email"],
                "password" => bcrypt($password),
                "created_at" => Carbon::now(),
                'mobile_verified_at' => $mobile_verified_at,
                'activation_code' => $code['value'],
                'activation_code_expire' => $code['time'],
                'country_code' => $phone['country_code'],
                'phone' => $phone['phone'],
                'town_id' => $data['town'],
            ]);

            $store_id = DB::table('stores')->insertGetId([
                "name" => $data["store_name"],
                "lng" => $data["location"]['lng'],
                "lat" => $data["location"]['lat'],
                "address" => $data["address"],
                'town_id' => $data['town'],
                "default_delivery_time" => $data["default_delivery_time"],
                "owner_id" => $id,
                "type_id" => $data["activity_type"],
                "created_at" => Carbon::now(),
            ]);

            StoreSetting::insert([
                "store_id" => $store_id,
                "delivery_perice_percentage" => 0,
                "taken_percentage_from_store" => null,
                "created_at" => Carbon::now()
            ]);

            $user = User::find($id);
            $user->assignRole('owner');
            return $user;
        });
        return $user;
    }

    public function update($store, $data)
    {
        $store->update([
            "name" => $data["store_name"],
            "lng" => $data["location"]['lng'],
            "lat" => $data["location"]['lat'],
            "address" => $data["address"],
            "town_id" => $data["town"],
            "default_delivery_time" => $data["default_delivery_time"],
            "type_id" => $data["activity_type"],
        ]);

        $store->owner->update([
            "name" => $data["owner_name"],
            "town_id" => $data["town"],
            "username" => $data["owner_username"],
            "email" => $data["owner_email"]
        ]);
    }

    public function updateMyStore($data)
    {
        $store = Auth::user()->store;
        $store->update($data);
    }

    public function mail($email_address)
    {
        Mail::send('test', ['token' => Str::random(60)], function (Message $message) use ($email_address) {
            $message->from('theimaginaryking@gmail.com');
            $message->to($email_address);
            $message->subject("reset password");
        });
    }

    public function delete($store)
    {
        $store->delete(); //nothing changes
    }
}
